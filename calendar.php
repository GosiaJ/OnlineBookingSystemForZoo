<?php 
//klasa wyboru miesiąca
class ChooseMonth{
	private $month;
	private $day;
	private $year;
	private $date;

	public function __construct(){
		$this->date = getdate();
		$this->month = $this->date['mon'];
		$this->day = $this->date['mday'];
		$this->year = $this->date['year'];
	}

	//metoda wyświetlająca formularz
	public function form(){

		//wybór miesiąca w bieżącym roku
		
		$form = '<form action="#" method="post">';
		$form .= '<select name="mon">';
		$monthArray = array(1 =>'Styczen', 'Luty', 'Marzec', 'Kwiecien', 'Maj', 'Czerwiec', 'Lipiec', 'Sierpien', 'Wrzesien', 'Pazdziernik', 'Listopad', 'Grudzien');
		for($counter=$this->month; $counter<=12; $counter++)
		{
			$form .= '<option value="'.$monthArray[$counter].'">' . $monthArray[$counter] . '</option>';	
		}
		$form .= '<input type="submit" name="chose" value="Wybierz">';
		$form .= '</select>';
		$form .= '</form>';

		echo $form;

		if(isset($_POST['chose']))
		{
			for($new=1; $new<=12; $new++)
			{
				if($_POST['mon'] === $monthArray[$new])
				{
					return  $new;
					break;
				}
				else
				{
					continue;
				}	
			}		 
		}
		else
		{
			return $this->month;
		}
		
	}

	public function takeYear(){
		return $this->year;
	}
}
//klasa kalendarza
class Calendar{
	//prywatne właściwośi klasy tylko widoczne w klasie, nie poza nią.
	private $month;
	private $year;
	private $days_of_week;
	private $num_days;
	private $date_info;
	private $day_of_week;

	public function __construct($month, $year, $days_of_week=array('Nd','Pon', 'Wt', 'Śr', 'Czw', 'Pt', 'Sob')){
		//miesiąc
		$this->month = $month;
		//rok
		$this->year = $year;
		//dzień tygodnia
		$this->days_of_week = $days_of_week;
		//ile dni ma miesiac
		$this->num_days = cal_days_in_month(CAL_GREGORIAN, $this->month, $this->year);
		//początek do pobrania pierwszego dnia miesiaca (pn/wt/śr ...)
		//getdate zawiera info o dniu
		$this->date_info = getdate(strtotime('first day of',mktime(0,0,0,$this->month,1,$this->year)));
		//pobierz pierwszy dzień wybranego miesiąca daje nam numer 0-6 gdzie 0 no niedziela!
		$this->day_of_week = $this->date_info['wday'];
	}

	//wyświetlanie kalendarza
	public function show(){
		require_once("connect.php");
		mysqli_report(MYSQLI_REPORT_STRICT);
		try
		{
			$connection = new mysqli($host, $db_user, $db_pas, $db_name);
			if($connection->errno > 0)
				throw new Exception(mysqli_connect_errno());
			else
			{
				$dataFromDB = @$connection->query("SELECT * FROM events");
				$i = 0;
				while($row = $dataFromDB->fetch_assoc())
				{
				$wydarzenieDzien = date('d',strtotime($row['date']));
				$wydarzenieMiesiac = date('F',strtotime($row['date']));

				if($wydarzenieMiesiac == 'January')
					$wydarzenieMiesiac = 1;
				elseif($wydarzenieMiesiac == 'February')
					$wydarzenieMiesiac = 2;
				elseif($wydarzenieMiesiac == 'March')
					$wydarzenieMiesiac = 3;
				elseif($wydarzenieMiesiac == 'April')
					$wydarzenieMiesiac = 4;
				elseif ($wydarzenieMiesiac == 'May') 
					$wydarzenieMiesiac = 5;
				elseif($wydarzenieMiesiac == 'June')
					$wydarzenieMiesiac = 6;
				elseif($wydarzenieMiesiac == 'July')
					$wydarzenieMiesiac = 7;
				elseif($wydarzenieMiesiac == 'August')
					$wydarzenieMiesiac = 8;
				elseif($wydarzenieMiesiac == 'September')
					$wydarzenieMiesiac = 9;
				elseif($wydarzenieMiesiac == 'October')
					$wydarzenieMiesiac = 10;
				elseif($wydarzenieMiesiac == 'November')
					$wydarzenieMiesiac = 11;
				else
					$wydarzenieMiesiac=12;

				$dayArray[$i] =  $wydarzenieDzien;
				$monthArray[$i] = $wydarzenieMiesiac;
				$nameArray[$i] = $row['name'];
				$i++;
				}

				//wyświetlanie miesiąca i roku
				$output = '<table class="calendar">';
				$output .= '<caption>' . $this->date_info['month'] . ' ' . $this->year . '</caption>';
				$output .= '<tr>';

				//wyświetlanie na sztywno dni tygodnia
				foreach($this->days_of_week as $day)
				{
					$output .= '<th class="header">' . $day . '</th>';
				}

				$output .= '</tr><tr>';

				//jak pierwszy dzień nie wypada w niedzielę????
				//wypełniamy wolną przestrzeń za pomocą colspan, który definije liczbę kolumn w wierszu, które mają zostać puste
				if($this->day_of_week > 0)
				{
					$output .= '<td colspan="' . $this->day_of_week . '"></td>';
				}

				//początek liczenia dni
				$current_day = 1;
				//pętla, w której tworzymy dni
				while($current_day <= $this->num_days)
				{
					if($this->day_of_week == 7)
					{
						$this->day_of_week = 0;
						$output .= '</tr><tr>';
					}
					//$zmienna w stanie niskim informuje nas o tym, że nie znaleziono żadnego dopasowania do daty z DB
					for($a=0; $a<count($dayArray);$a++)
					{
						if($dayArray[$a] == $current_day && $monthArray[$a] == $this->month)
						{
							$output .= '<td class="day">' . $current_day . ' <p>' . $nameArray[$a] . '</p></td>';
							$zmienna = 1;
							break;
						}
						elseif($dayArray[$a] != $current_day)
						{
							$zmienna = 0;
						}
					}
					if($zmienna == 0)
					{
						$output .= '<td class="day">' . $current_day . '</td>';
						$amienna = 1;
					}

					//zwiększanie licznika
					$current_day++;
					$this->day_of_week++;
				}

				//jeżeli miesiąc nie kończy się na sobocie, znowu wypełniamy pozostałe, puste miejsca.
				if($this->day_of_week != 7)
				{
					$reamining_days = 7 - $this->day_of_week;
					$output .= '<td colspan="' . $reamining_days . '"></td>';
				}

				$output .= '</tr>';
				$output .= '</table>';
		}
	}
		catch(Exception $e)
		{
			echo $e;
		}

		echo $output;
	}
}
 ?>
