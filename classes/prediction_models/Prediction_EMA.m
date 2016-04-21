function prediction = Prediction_EMA(close)

daysBack = 10;
alpha = 2 / (daysBack + 1); %calculate smoothing factor "alpha"
coefficient = repmat(1-alpha,1,daysBack).^(1:daysBack); %note 1-alpha

SMA = 1:10;
for n = 1:10        % Calculate SMA for first 10
    SMA(n) = sum(close(1:n))/n;
end

EMA = filter(coefficient, sum(coefficient), close);
EMA(1:10) = SMA;


prediction = interp1(1:size(EMA),EMA,size(EMA):(size(EMA)+5),'pchip');

%For reference %
plot(close,'--')
hold on
plot(EMA,'r')
hold on
plot(size(EMA):size(EMA)+5,prediction,'g');