function [fiveDayPrice, confidence, actual, estimate] = ANN(data, N, delay) 

traindata = length(data)-N;
        
        %allocate memory for training data
        inputSeries = cell(1,traindata); %secondary data (open, high, low, volume)
        targetSeries = cell(1,traindata); %primary data to be predicted (close)
        
        %allocate memory for prediction data
        inputSeries2 = cell(1,N); %secondary data (open, high, low, volume)
        targetSeries2 = cell(1,N); %primary data to be predicted (close)
        
        %fill in data from the query data
        for i=1:traindata
           inputSeries{i} = [data{i, 1}; data{i, 2}; data{i, 3}; data{i, 5}];
           targetSeries{i} = data{i, 4};
        end
        for i=traindata+1:length(data)
           inputSeries2{i-traindata} = [data{i, 1}; data{i, 2}; data{i, 3}; data{i, 5}];
           targetSeries2{i-traindata} = data{i, 4};
        end
     
%Create a Nonlinear Autoregressive Network with External Input
%number of previous days used to predict the future price
%comutation time drastically increases as delay increases
inputDelays = 1:delay;
feedbackDelays = 1:delay;

%size of hidden layers
%comutation time drastically increases as size increases
hiddenLayerSize = 50;

%create narxnet network
net = narxnet(inputDelays,feedbackDelays,hiddenLayerSize);

% Prepare the Data for Training and Simulation
% The function PREPARETS prepares timeseries data for a particular network,
% shifting time by the minimum amount to fill input states and layer states.
% Using PREPARETS allows you to keep your original time series data unchanged, while
% easily customizing it for networks with differing numbers of delays, with
% open loop or closed loop feedback modes.
[inputs,inputStates,layerStates,targets] = preparets(net,inputSeries,{},targetSeries);

% Setup Division of Data for Training, Validation, Testing
net.divideParam.trainRatio = 70/100;
net.divideParam.valRatio = 15/100;
net.divideParam.testRatio = 15/100;

% Train the Network
[net,tr] = train(net,inputs,targets,inputStates,layerStates);

% Test the Network

errorRange = 1; %the threshold used to calculate the probability the a prediction is likely to occur 

outputs = net(inputs,inputStates,layerStates); %training outputs

%convert training outputs and targets from cell arrays to vectors
estimate = cell2mat(outputs);
actual = cell2mat(targets);

%calculate error z value
error = (errorRange/2)/std(gsubtract(actual,estimate));
%convert z value to percentage
confidence = (0.5 * erfc(-error ./ sqrt(2)))*100


% Plots
% Uncomment these lines to enable various plots.
%figure, plotperform(tr)
%figure, plottrainstate(tr)
%figure, plotregression(targets,outputs)
%figure, plotresponse(targets,outputs)
%figure, ploterrcorr(errors)
%figure, plotinerrcorr(inputs,errors)

% Closed Loop Network
% Use this network to do multi-step prediction.
% The function CLOSELOOP replaces the feedback input with a direct
% connection from the outout layer.

%secondary data series used for prediction
inputSeriesPred  = [inputSeries(end-delay+1:end),inputSeries2];
%primary data series used for prediction concatenated with NANS for closing
%prices yet unknown
targetSeriesPred = [targetSeries(end-delay+1:end), con2seq(nan(1,N))];

netc = closeloop(net); %close the loop
netc.name = [net.name ' - Closed Loop'];

%prepare data
[xc,xic,aic,tc] = preparets(netc,inputSeriesPred,{},targetSeriesPred);
%yc is the closed loop output
yc = netc(xc,xic,aic);
%we only care about the last 5 days since we already know the first and
%second
fiveDayPrice = cell2mat(yc);