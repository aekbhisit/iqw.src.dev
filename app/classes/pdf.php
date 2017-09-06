<?php
class PDF extends Database {
	var $module   ;
	var $smtp ;
	public function __construct($module=NULL,$table=NULL){
		$this->module = (empty($table))?$module:$table  ;
		 parent::__construct((empty($table))?$module:$table );
		 
	}
	
// function for mailer  /////////////////////////////////////////////////
// Develop by iQuickweb.com 28/06/2012
///////////////////////////////////////////////////////////////////////////////////
	public function createPDFFile($header='',$data,$footer='',$saveTo){
		$pdf=new FPDF();
		$pdf->AddPage();
		$pdf->AddFont('angsa','','angsa.php');
		$pdf->Cell(0,20,iconv( 'UTF-8','TIS-620','สวัสดี ชาวไทยครีเอท'),0,1,"C");
		$pdf->Output($saveTo,"F");
	}
	
	public function html2pdf($content,$output,$sens='P',$format='A4'){
// * @param	string		$sens - landscape or portrait orientation
// * @param	string		$format - format A4, A5, ...
// * @param	string		$langue - language: fr, en, it ... 
// * @param	boolean		$unicode - TRUE means clustering the input text IS unicode (default = true)
// * @param 	String		$encoding - charset encoding; Default is UTF-8
// * @param	array		$marges - margins by default, in order (left, top, right, bottom)
// * @return	null
		$html2pdf = new HTML2PDF($sens, $format);
        $html2pdf->pdf->SetDisplayMode('real');
        $html2pdf->writeHTML($content);
        $html2pdf->Output($output, 'F');
	}
}
?>