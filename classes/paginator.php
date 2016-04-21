<?php

// written by: Mohammed Latif
// tested by: Mohammed Latif
// debugged by: Mohammed Latif

include_once 'searcher.php';

class paginator {
	private $searcher;
	private $itemsPerPage;
	private $totalPages;
	private $currentPage;
	private $range;
	private $url;
	private $offset;
	private $totalItems;
	
	public function __construct() {
		$this->searcher = new searcher();
		$this->itemsPerPage = 5;
		$this->range = 3;
	}
	
	//search for a keyword
	public function search($keyword) {
		//page url
		$this->url = $_SERVER[PHP_SELF]."?q=".$keyword."&search=Submit";
		$this->totalItems = $this->searcher -> getSearchCount($keyword);
		$this->totalPages = ceil($this->totalItems / $this->itemsPerPage);

		// get the current page or set a default
		if (isset($_GET['page']) && is_numeric($_GET['page'])) {
  		// cast var as int
  		$this->currentPage = (int) $_GET['page'];
		} else {
	   	// default page num
   		$this->currentPage = 1;
		}
		
		// if current page is greater than total pages set current page to last page
		if ($this->currentPage > $this->totalPages) $this->currentPage = $this->totalPages;
		
		// if current page is less than first page set current page to first page
		if ($this->currentPage < 1) $this->currentPage = 1;
		
		// the offset of the list, based on current page 
		$this->offset = ($this->currentPage - 1) * $this->itemsPerPage;

		$stockIDs = $this->searcher -> search($keyword, $this->offset, $this->itemsPerPage);
		
		return $stockIDs;
	}
	
	public function suggest() {
		$this->url = $_SERVER[PHP_SELF]."?suggest=Submit";
		$this->totalItems = 10;
		$this->totalPages = ceil($this->totalItems / $this->itemsPerPage);

		// get the current page or set a default
		if (isset($_GET['page']) && is_numeric($_GET['page'])) {
  		// cast var as int
  		$this->currentPage = (int) $_GET['page'];
		} else {
	   	// default page num
   		$this->currentPage = 1;
		}
		
		// if current page is greater than total pages set current page to last page
		if ($this->currentPage > $this->totalPages) $this->currentPage = $this->totalPages;
		
		// if current page is less than first page set current page to first page
		if ($this->currentPage < 1) $this->currentPage = 1;
		
		// the offset of the list, based on current page 
		$this->offset = ($this->currentPage - 1) * $this->itemsPerPage;

		$stockIDs = $this->searcher -> suggest($this->offset, $this->itemsPerPage);
		
		return $stockIDs;
	}
	
	public function getPageData() {
		return array('totalItems'=>$this->totalItems, 'offset'=>$this->offset, 'itemsPerPage'=>$this->itemsPerPage);
	}
		
	public function createPagination() {
		// range of num links to show
		$range = 3;
		
		$prev = $this->currentPage - 1;
		$next = $this->currentPage + 1;
		$lastPage = $this->totalPages;
		$lastPagem1 = $this->totalPages - 1;

		$html = '';
		
		
		if ($lastPage > 1) {
			$html .= "<div class='paginate'>";
			if($this->currentPage > 1) {
				$html .= "<a href='".$this->url."&page=".$prev."'>Previous</a>";
			}
			else {
				$html .= "<span class='disabled'>Previous</span>";
			}
			
			if ($lastPage < (7 + $range * 2)) {
				
				for ($counter = 1; $counter <= $lastPage; $counter++) {
					if($counter == $this->currentPage) {
						$html .= "<span class='current'>".$counter."</span>";
					} else {
						$html .= "<a href='".$this->url."&page=".$counter."'>".$counter."</a>";
					}
				}
			}
			
			else if ($lastPage > (5 + $range * 2)) {
				if($this->currentPage < (1 + $range * 2)) {
                	for ($counter = 1; $counter < (4 + $range * 2); $counter++) {
                    	if ($counter == $this->currentPage){
                        	$html .= "<span class='current'>".$counter."</span>";
                    	} else {
                        	$html .= "<a href='".$this->url."&page=".$counter."'>".$counter."</a>";                
               			}
               		}
                $html .= "...";
				$html .= "<a href='".$this->url."&page=".$lastPagem1."'>".$lastPagem1."</a>";
				$html .= "<a href='".$this->url."&page=".$lastPage."'>".$lastPage."</a>";
            	}

			}
			// Middle hide some front and some back
            else if ($lastPage - ($range * 2) > $this->currentPage && $this->currentPage > ($range * 2)) {
				$html .= "<a href='".$this->url."&page=1'>1</a>";
				$html .= "<a href='".$this->url."&page=2'>2</a>";
                $html.= "...";
                for ($counter = $this->currentPage - $range; $counter <= $this->currentPage + $range; $counter++) {
                    if ($counter == $this->curentpage){
                        $html .= "<span class='current'>".$counter."</span>";
                    } else {
                        $html .= "<a href='".$this->url."&page=".$counter."'>".$counter."</a>";                
               		}
                	$html.= "...";
					$html .= "<a href='".$this->url."&page=".$lastPagem1."'>".$lastPagem1."</a>";
					$html .= "<a href='".$this->url."&page=".$lastPage."'>".$lastPage."</a>";    
           		}
           	}
            // End only hide early pages
            else {
                $html .= "<a href='".$this->url."&page=1'>1</a>";
				$html .= "<a href='".$this->url."&page=2'>2</a>";
                $html .= "...";
                for ($counter = $lastPage - (2 + ($range * 2)); $counter <= $lastPage; $counter++) {
                    if ($counter == $this->currentPage){
                    	$html .= "<span class='current'>".$counter."</span>";
                    } else {
                    	$html .= "<a href='".$this->url."&page=".$counter."'>".$counter."</a>";                
               		}
            	}
       	 	}
       	 	// Next
        	if ($this->currentPage < $lastPage){
				$html .= "<a href='".$this->url."&page=".$next."'>Next</a>";
        	} else {
				$html .= "<span class='disabled'>Next</span>";
            }
             
            $html .= "</div>";       
		}
		return $html;
	}	
}

?>