<? 
require_once($_SERVER["DOCUMENT_ROOT"]."/local/report/crest.php");

if(isset($_GET["date"])){

    $date = $_GET["date"];

}
if(isset($_GET["datenow"])){

    $datenow = $_GET["datenow"];
}


//--------------------собираем все сделки за отчётный период-----------------------------begin
$start = 0;
$array = [];
$deal_won = CRest::call(
                     'crm.deal.list', 
                      [
						 'filter' => array("CATEGORY_ID" => "31", ">DATE_CREATE" => $date, "<DATE_CREATE" => $datenow),
						  'select' => array("ID", "TITLE", "STAGE_ID", "OPPORTUNITY", "UF_CRM_1744364236", "UF_CRM_1744364295", "UF_CRM_1744875443347", "UF_CRM_1748263562854",
												"UF_CRM_1749011297", "UF_CRM_1744875526"),
						]
                     );

$counter = $deal_won['total'];

if ($counter > 0)
{
	while ($counter > $start)
	{
		$deal_won_1 = CRest::call(
                     'crm.deal.list', 
                      [
						'order' => array("ID" => "DESC"),
						 'filter' => array("CATEGORY_ID" => "31", ">DATE_CREATE" => $date, "<DATE_CREATE" => $datenow),
						  'select' => array("ID", "TITLE", "STAGE_ID", "OPPORTUNITY", "UF_CRM_1744364236", "UF_CRM_1744364295", "UF_CRM_1744875443347", "UF_CRM_1748263562854",
												"UF_CRM_1749011297", "UF_CRM_1744875526"),
						'start' => $start ]);
		array_push($array, $deal_won_1);
		$start += 50;
	}
	for ($i=0; $i<count($array); $i++)
	{

		for ($j=0; $j<count($array[$i]['result']); $j++)
		{
			$deal_all[] = $array[$i]['result'][$j];


		}

	}
}
//--------------------собираем все сделки за отчётный период-----------------------------end

//echo '<pre>';
//print_r($deal_all);
//echo '</pre>';

$cell_color = "";

for ($i=0; $i < count($deal_all); $i++)
{
	if ($deal_all[$i]['STAGE_ID'] == "C31:NEW")
		{
			$deal_all[$i]['STAGE_ID'] = "Новая претензия";
			$deal_all[$i]['CELL_COLOR'] = "#fff55a";
		}
	if ($deal_all[$i]['STAGE_ID'] == "C31:PREPAYMENT_INVOIC")
		{
			$deal_all[$i]['STAGE_ID'] = "Подача претензии";
			$deal_all[$i]['CELL_COLOR'] = "#55d0e0";
		}
	if ($deal_all[$i]['STAGE_ID'] == "C31:EXECUTING")
		{
			$deal_all[$i]['STAGE_ID'] = "Ответ по претензии";
			$deal_all[$i]['CELL_COLOR'] = "#47e4c2";
		}
	if ($deal_all[$i]['STAGE_ID'] == "C31:UC_IU7ONF")
		{
			$deal_all[$i]['STAGE_ID'] = "Объединение товаров";
			$deal_all[$i]['CELL_COLOR'] = "#ff5752";
		}
	if ($deal_all[$i]['STAGE_ID'] == "C31:UC_P35PS7")
		{
			$deal_all[$i]['STAGE_ID'] = "Запрос АСЦ";
			$deal_all[$i]['CELL_COLOR'] = "#9d54d1";
		}
	if ($deal_all[$i]['STAGE_ID'] == "C31:UC_1EPH96")
		{
			$deal_all[$i]['STAGE_ID'] = "В работе (АСЦ)";
			$deal_all[$i]['CELL_COLOR'] = "#fff300";
		}
	if ($deal_all[$i]['STAGE_ID'] == "C31:UC_VL7TJS")
		{
			$deal_all[$i]['STAGE_ID'] = "Акты АСЦ";
			$deal_all[$i]['CELL_COLOR'] = "#468ee5";
		}
	if ($deal_all[$i]['STAGE_ID'] == "C31:FINAL_INVOICE")
		{
			$deal_all[$i]['STAGE_ID'] = "Экспертиза";
			$deal_all[$i]['CELL_COLOR'] = "#ffa900";
		}
	if ($deal_all[$i]['STAGE_ID'] == "C31:UC_VBV2IJ")
		{
			$deal_all[$i]['STAGE_ID'] = "Досудебная претензия";
			$deal_all[$i]['CELL_COLOR'] = "#75d900";
		}
	if ($deal_all[$i]['STAGE_ID'] == "C31:UC_6WYD7G")
		{
			$deal_all[$i]['STAGE_ID'] = "Ответ на досудебную претензию";
			$deal_all[$i]['CELL_COLOR'] = "#ffab00";
		}
	if ($deal_all[$i]['STAGE_ID'] == "C31:WON")
		{
			$deal_all[$i]['STAGE_ID'] = "Претензия удовлетв";
			$deal_all[$i]['CELL_COLOR'] = "#7bd500";
		}
	if ($deal_all[$i]['STAGE_ID'] == "C31:LOSE")
		{
			$deal_all[$i]['STAGE_ID'] = "Претензия не удовлетв";
			$deal_all[$i]['CELL_COLOR'] = "#ff5752";
		}
	if ($deal_all[$i]['STAGE_ID'] == "C31:APOLOGY")
		{
			$deal_all[$i]['STAGE_ID'] = "Суд";
			$deal_all[$i]['CELL_COLOR'] = "#ff5752";
		}
	if ($deal_all[$i]['STAGE_ID'] == "C31:UC_1XCMGN")
		{
			$deal_all[$i]['STAGE_ID'] = "Дубль-заявка";
			$deal_all[$i]['CELL_COLOR'] = "#f11716";
		}
}


//echo '<pre>';
//print_r($deal_all);
//echo '</pre>';

/////////////////////////////////
/////OZON FBS //////////////////////
///////////////////////////////

$deal_all_ozon_fbs = [];

for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5037" && $deal_all[$i]['UF_CRM_1744364295'] == "5039")
	{
	$print_title = $deal_all[$i]['TITLE'];
	$deal_all_ozon_fbs[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "REAL_OPPORTUNITY" => $deal_all[$i]['UF_CRM_1748263562854'], "COMPENS_OPPORTUNITY" => $deal_all[$i]['UF_CRM_1744875526'], "TIME_TAKE" => $deal_all[$i]['UF_CRM_1749011297'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347'],
	"LINK" => "<a target='_blank' href=https://b24.topcomputer.ru/crm/deal/details/".$deal_all[$i]['ID']."/>".$print_title."</a>", "CELL_COLOR" => $deal_all[$i]['CELL_COLOR']);
	$summ_deal_all_ozon_fbs += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_all_ozon_fbs += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_all_ozon_fbs += $deal_all[$i]['UF_CRM_1744875526'];
	$summ_time_deal_all_ozon_fbs += $deal_all[$i]['UF_CRM_1749011297'];
	$summ_time_deal_all_ozon_fbs_average = $summ_time_deal_all_ozon_fbs/count($deal_all_ozon_fbs);
}
}


//echo '<pre>';
//print_r($deal_all_ozon_fbs);
//echo '</pre>';

for ($i=0; $i<count($deal_all_ozon_fbs); $i++)
{
	$price = $deal_all_ozon_fbs[$i]['REAL_OPPORTUNITY'];

	$str = strpos($price, "|");
	$row = substr($price, 0, $str);


	$price_cimpens = $deal_all_ozon_fbs[$i]['COMPENS_OPPORTUNITY'];

	$str_compens = strpos($price_cimpens, "|");
	$row_compens = substr($price_cimpens, 0, $str_compens);

	$link_deal_all_ozon_fbs[] = array("LINK" => $deal_all_ozon_fbs[$i]['LINK'], "STAGE" => $deal_all_ozon_fbs[$i]['STAGE_ID'], "SUMM" => $deal_all_ozon_fbs[$i]['OPPORTUNITY'], "REAL_SUMM" => $row, "COMPENS_SUMM" => $row_compens, "CELL_COLOR" => $deal_all_ozon_fbs[$i]['CELL_COLOR']);
}



// все претензии OZON FBS
//echo '<pre>';
//print_r($link_deal_all_ozon_fbs);
//echo '</pre>';

////////////////////////////

$deal_new_ozon_fbs = [];

for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5037" && $deal_all[$i]['UF_CRM_1744364295'] == "5039" && $deal_all[$i]['STAGE_ID'] == "Новая претензия")
	{
	$deal_new_ozon_fbs[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347'], "TIME_TAKE" => $deal_all[$i]['UF_CRM_1749011297']);
	$summ_deal_new_ozon_fbs += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_new_ozon_fbs += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_new_ozon_fbs += $deal_all[$i]['UF_CRM_1744875526'];
		//	$summ_time_deal_new_ozon_fbs += $deal_all[$i]['UF_CRM_1749011297'];
		// $summ_time_deal_new_ozon_fbs = $summ_time_deal_new_ozon_fbs/count($deal_new_ozon_fbs);
	}
}
// все НОВЫЕ претензии OZON FBS
//echo '<pre>';
//print_r($summ_time_deal_new_ozon_fbs);
//echo '</pre>';

////////////////////////////



$deal_get_ozon_fbs = [];

for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5037" && $deal_all[$i]['UF_CRM_1744364295'] == "5039" && $deal_all[$i]['STAGE_ID'] == "Подача претензии")
	{
	$deal_get_ozon_fbs[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347']);
	$summ_deal_get_ozon_fbs += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_get_ozon_fbs += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_get_ozon_fbs += $deal_all[$i]['UF_CRM_1744875526'];
	}
}

// Поданные претензии OZON FBS
//echo '<pre>';
//print_r($deal_get_ozon_fbs);
//echo '</pre>';

////////////////////////////

$deal_answer_ozon_fbs = [];

for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5037" && $deal_all[$i]['UF_CRM_1744364295'] == "5039" && $deal_all[$i]['STAGE_ID'] == "Ответ по претензии")
	{
	$deal_answer_ozon_fbs[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347']);
	$summ_deal_answer_ozon_fbs += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_answer_ozon_fbs += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_answer_ozon_fbs += $deal_all[$i]['UF_CRM_1744875526'];
	}
}

// Ответ по претензии OZON FBS
//echo '<pre>';
//print_r($deal_get_ozon_fbs);
//echo '</pre>';

////////////////////////////

$deal_preparation_ozon_fbs = [];

for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5037" && $deal_all[$i]['UF_CRM_1744364295'] == "5039" && $deal_all[$i]['STAGE_ID'] == "Объединение товаров")
	{
	$deal_preparation_ozon_fbs[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347']);
	$summ_deal_preparation_ozon_fbs += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_preparation_ozon_fbs += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_preparation_ozon_fbs += $deal_all[$i]['UF_CRM_1744875526'];
	}
}
// Подготовка к экспертизе OZON FBS
//echo '<pre>';
//print_r($deal_get_ozon_fbs);
//echo '</pre>';

////////////////////////////


$deal_won_ozon_fbs = [];

for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5037" && $deal_all[$i]['UF_CRM_1744364295'] == "5039" && $deal_all[$i]['STAGE_ID'] == "Претензия удовлетв")
	{
	$deal_won_ozon_fbs[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347']);
	$summ_deal_won_ozon_fbs += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_won_ozon_fbs += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_won_ozon_fbs += $deal_all[$i]['UF_CRM_1744875526'];
	}
}


// все УДОВЛЕТВОРЕННЫЕ претензии OZON FBS
//echo '<pre>';
//print_r($deal_won_ozon_fbs);
//echo '</pre>';

////////////////////////////

$deal_won_partly_ozon_fbs = [];

for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5037" && $deal_all[$i]['UF_CRM_1744364295'] == "5039" && $deal_all[$i]['UF_CRM_1744875443347'] == "5055")
	{
	$deal_won_partly_ozon_fbs[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347']);
	$summ_deal_won_partly_ozon_fbs += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_won_partly_ozon_fbs += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_won_partly_ozon_fbs += $deal_all[$i]['UF_CRM_1744875526'];
	}
}
// все ЧАСТИЧНО УДОВЛЕТВОРЕННЫЕ претензии OZON FBS
//echo '<pre>';
//print_r($deal_won_partly_ozon_fbs);
//echo '</pre>';



////////////////////////////

$deal_lose_ozon_fbs = [];
for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5037" && $deal_all[$i]['UF_CRM_1744364295'] == "5039" && $deal_all[$i]['STAGE_ID'] == "Претензия не удовлетв")
	{
	$deal_lose_ozon_fbs[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347']);
	$summ_deal_lose_ozon_fbs += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_lose_ozon_fbs += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_lose_ozon_fbs += $deal_all[$i]['UF_CRM_1744875526'];
	}
}
// все НЕ УДОВЛЕТВОРЕННЫЕ претензии OZON FBS
//echo '<pre>';
//print_r($deal_lose_ozon_fbs);
//echo '</pre>';



//echo '<pre>';
//print_r($link_deal_all);
//echo '</pre>';

/////////////////////////////////
/////OZON FBO //////////////////////
///////////////////////////////


$deal_all_ozon_fbo = [];

for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5037" && $deal_all[$i]['UF_CRM_1744364295'] == "5040")
	{
	$print_title = $deal_all[$i]['TITLE'];
	$deal_all_ozon_fbo[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "REAL_OPPORTUNITY" => $deal_all[$i]['UF_CRM_1748263562854'], "COMPENS_OPPORTUNITY" => $deal_all[$i]['UF_CRM_1744875526'], "TIME_TAKE" => $deal_all[$i]['UF_CRM_1749011297'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347'],
	"LINK" => "<a target='_blank' href=https://b24.topcomputer.ru/crm/deal/details/".$deal_all[$i]['ID']."/>".$print_title."</a>", "CELL_COLOR" => $deal_all[$i]['CELL_COLOR']);
	$summ_deal_all_ozon_fbo += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_all_ozon_fbo += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_all_ozon_fbo += $deal_all[$i]['UF_CRM_1744875526'];
	$summ_time_deal_all_ozon_fbo += $deal_all[$i]['UF_CRM_1749011297'];
	$summ_time_deal_all_ozon_fbo_average = $summ_time_deal_all_ozon_fbo/count($deal_all_ozon_fbo);
	}
}


for ($i=0; $i<count($deal_all_ozon_fbo); $i++)
{
	$price_fbo = $deal_all_ozon_fbo[$i]['REAL_OPPORTUNITY'];

	$str_fbo = strpos($price_fbo, "|");
	$row_fbo = substr($price_fbo, 0, $str_fbo);

	$price_fbo_compens = $deal_all_ozon_fbo[$i]['COMPENS_OPPORTUNITY'];

	$str_fbo_compens = strpos($price_fbo_compens, "|");
	$row_fbo_compens = substr($price_fbo_compens, 0, $str_fbo_compens);

	$link_deal_all_ozon_fbo[] = array("LINK" => $deal_all_ozon_fbo[$i]['LINK'], "STAGE" => $deal_all_ozon_fbo[$i]['STAGE_ID'], "SUMM" =>$deal_all_ozon_fbo[$i]['OPPORTUNITY'], "REAL_SUMM" => $row_fbo, "COMPENS_SUMM" => $row_fbo_compens, "CELL_COLOR" => $deal_all_ozon_fbo[$i]['CELL_COLOR']);
}


// все претензии OZON FBO
//echo '<pre>';
//print_r($link_deal_all_ozon_fbo);
//echo '</pre>';

////////////////////////////

$deal_new_ozon_fbo = [];

for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5037" && $deal_all[$i]['UF_CRM_1744364295'] == "5040" && $deal_all[$i]['STAGE_ID'] == "Новая претензия")
	{
	$deal_new_ozon_fbo[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347'], "TIME_TAKE" => $deal_all[$i]['UF_CRM_1749011297']);
	$summ_deal_new_ozon_fbo += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_new_ozon_fbo += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_new_ozon_fbo += $deal_all[$i]['UF_CRM_1744875526'];
		// $summ_time_deal_new_ozon_fbo += $deal_all[$i]['UF_CRM_1749011297'];
		// $summ_time_deal_new_ozon_fbo = $summ_time_deal_new_ozon_fbo/count($deal_new_ozon_fbo);
	}
}
// все НОВЫЕ претензии OZON FBO
//echo '<pre>';
//print_r($deal_new_ozon_fbo);
//echo '</pre>';

////////////////////////////



$deal_get_ozon_fbo = [];

for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5037" && $deal_all[$i]['UF_CRM_1744364295'] == "5040" && $deal_all[$i]['STAGE_ID'] == "Подача претензии")
	{
	$deal_get_ozon_fbo[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347']);
	$summ_deal_get_ozon_fbo += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_get_ozon_fbo += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_get_ozon_fbo += $deal_all[$i]['UF_CRM_1744875526'];
	}
}

// Поданные претензии OZON FBO
//echo '<pre>';
//print_r($deal_new_ozon_fbo);
//echo '</pre>';

////////////////////////////


$deal_answer_ozon_fbo = [];

for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5037" && $deal_all[$i]['UF_CRM_1744364295'] == "5040" && $deal_all[$i]['STAGE_ID'] == "Ответ по претензии")
	{
	$deal_answer_ozon_fbo[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347']);
	$summ_deal_answer_ozon_fbo += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_answer_ozon_fbo += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_answer_ozon_fbo += $deal_all[$i]['UF_CRM_1744875526'];
	}
}

// Ответ по претензии OZON FBO
//echo '<pre>';
//print_r($deal_new_ozon_fbo);
//echo '</pre>';

////////////////////////////


$deal_preparation_ozon_fbo = [];

for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5037" && $deal_all[$i]['UF_CRM_1744364295'] == "5040" && $deal_all[$i]['STAGE_ID'] == "Объединение товаров")
	{
	$deal_preparation_ozon_fbo[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347']);
	$summ_deal_preparation_ozon_fbo += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_preparation_ozon_fbo += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_preparation_ozon_fbo += $deal_all[$i]['UF_CRM_1744875526'];
	}
}

// Подготовка к экспертизе OZON FBO
//echo '<pre>';
//print_r($deal_new_ozon_fbo);
//echo '</pre>';

////////////////////////////

$deal_won_ozon_fbo = [];

for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5037" && $deal_all[$i]['UF_CRM_1744364295'] == "5040" && $deal_all[$i]['STAGE_ID'] == "Претензия удовлетв")
	{
	$deal_won_ozon_fbo[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347']);
	$summ_deal_won_ozon_fbo += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_won_ozon_fbo += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_won_ozon_fbo += $deal_all[$i]['UF_CRM_1744875526'];
	}
}
// все УДОВЛЕТВОРЕННЫЕ претензии OZON FBO
//echo '<pre>';
//print_r($deal_won_ozon_fbo);
//echo '</pre>';

////////////////////////////

$deal_won_partly_ozon_fbo = [];

for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5037" && $deal_all[$i]['UF_CRM_1744364295'] == "5040" && $deal_all[$i]['UF_CRM_1744875443347'] == "5055")
	{
	$deal_won_partly_ozon_fbo[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347']);
	$summ_deal_won_partly_ozon_fbo += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_won_partly_ozon_fbo += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_won_partly_ozon_fbo += $deal_all[$i]['UF_CRM_1744875526'];
	}
}
// все ЧАСТИЧНО УДОВЛЕТВОРЕННЫЕ претензии OZON FBO
//echo '<pre>';
//print_r($deal_won_partly_ozon_fbo);
//echo '</pre>';



////////////////////////////

$deal_lose_ozon_fbo = [];
for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5037" && $deal_all[$i]['UF_CRM_1744364295'] == "5040" && $deal_all[$i]['STAGE_ID'] == "Претензия не удовлетв")
	{
	$deal_lose_ozon_fbo[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347']);
	$summ_deal_lose_ozon_fbo += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_lose_ozon_fbo += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_lose_ozon_fbo += $deal_all[$i]['UF_CRM_1744875526'];
	}
}
// все НЕ УДОВЛЕТВОРЕННЫЕ претензии OZON FBO
//echo '<pre>';
//print_r($deal_lose_ozon_fbo);
//echo '</pre>';


/////////////////////////////////
/////Яндекс-Маркет DBS //////////////////////
///////////////////////////////


$deal_all_yamarket_dbs = [];


for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5038" && $deal_all[$i]['UF_CRM_1744364295'] == "5098")
	{
	$print_title = $deal_all[$i]['TITLE'];
	$deal_all_yamarket_dbs[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "REAL_OPPORTUNITY" => $deal_all[$i]['UF_CRM_1748263562854'], "COMPENS_OPPORTUNITY" => $deal_all[$i]['UF_CRM_1744875526'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347'],
	"LINK" => "<a target='_blank' href=https://b24.topcomputer.ru/crm/deal/details/".$deal_all[$i]['ID']."/>".$print_title."</a>", "CELL_COLOR" => $deal_all[$i]['CELL_COLOR']);
	$summ_deal_all_yamarket_dbs += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_all_yamarket_dbs += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_all_yamarket_dbs += $deal_all[$i]['UF_CRM_1744875526'];
	$summ_time_deal_all_yamarket_dbs += $deal_all[$i]['UF_CRM_1749011297'];
	$summ_time_deal_all_yamarket_dbs_average = $summ_time_deal_all_yamarket_dbs/count($deal_all_yamarket_dbs);
	}
}

for ($i=0; $i<count($deal_all_yamarket_dbs); $i++)
{
	$price_dbs = $deal_all_yamarket_dbs[$i]['REAL_OPPORTUNITY'];

	$str_dbs = strpos($price_dbs, "|");
	$row_dbs = substr($price_dbs, 0, $str_dbs);

	$price_dbs_compens = $deal_all_yamarket_dbs[$i]['COMPENS_OPPORTUNITY'];

	$str_dbs_compens = strpos($price_dbs_compens, "|");
	$row_dbs_compens = substr($price_dbs_compens, 0, $str_dbs_compens);
	$link_deal_all_yamarket_dbs[] = array("LINK" => $deal_all_yamarket_dbs[$i]['LINK'], "STAGE" => $deal_all_yamarket_dbs[$i]['STAGE_ID'], "SUMM" =>$deal_all_yamarket_dbs[$i]['OPPORTUNITY'], "REAL_SUMM" => $row_dbs, "COMPENS_SUMM" => $row_dbs_compens, "CELL_COLOR" => $deal_all_yamarket_dbs[$i]['CELL_COLOR']);

}



// все претензии Яндекс-Маркет DBS
//echo '<pre>';
//print_r($deal_all_yamarket_dbs);
//echo '</pre>';

////////////////////////////

$deal_new_yamarket_dbs = [];

for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5038" && $deal_all[$i]['UF_CRM_1744364295'] == "5098" && $deal_all[$i]['STAGE_ID'] == "Новая претензия")
	{
	$deal_new_yamarket_dbs[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347'], "TIME_TAKE" => $deal_all[$i]['UF_CRM_1749011297']);
	$summ_deal_new_yamarket_dbs += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_new_yamarket_dbs += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_new_yamarket_dbs += $deal_all[$i]['UF_CRM_1744875526'];
	}
}
// все НОВЫЕ претензии Яндекс-Маркет DBS
//echo '<pre>';
//print_r($deal_new_yamarket_dbs);
//echo '</pre>';

////////////////////////////

$deal_get_yamarket_dbs = [];

for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5038" && $deal_all[$i]['UF_CRM_1744364295'] == "5098" && $deal_all[$i]['STAGE_ID'] == "Подача претензии")
	{
	$deal_get_yamarket_dbs[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347']);
	$summ_deal_get_yamarket_dbs += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_get_yamarket_dbs += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_get_yamarket_dbs += $deal_all[$i]['UF_CRM_1744875526'];
	}
}
// Подача претензии Яндекс-Маркет DBS
//echo '<pre>';
//print_r($deal_new_yamarket_dbs);
//echo '</pre>';

////////////////////////////


$deal_answer_yamarket_dbs = [];

for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5038" && $deal_all[$i]['UF_CRM_1744364295'] == "5098" && $deal_all[$i]['STAGE_ID'] == "Ответ по претензии")
	{
	$deal_answer_yamarket_dbs[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347']);
	$summ_deal_answer_yamarket_dbs += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_answer_yamarket_dbs += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_answer_yamarket_dbs += $deal_all[$i]['UF_CRM_1744875526'];
	}
}
// Ответ по претензии Яндекс-Маркет DBS
//echo '<pre>';
//print_r($deal_new_yamarket_dbs);
//echo '</pre>';

////////////////////////////

$deal_preparation_yamarket_dbs = [];

for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5038" && $deal_all[$i]['UF_CRM_1744364295'] == "5098" && $deal_all[$i]['STAGE_ID'] == "Объединение товаров")
	{
	$deal_preparation_yamarket_dbs[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347']);
	$summ_deal_preparation_yamarket_dbs += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_preparation_yamarket_dbs += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_preparation_yamarket_dbs += $deal_all[$i]['UF_CRM_1744875526'];
	}
}
// Подготовка к экспертизе Яндекс-Маркет DBS
//echo '<pre>';
//print_r($deal_new_yamarket_dbs);
//echo '</pre>';

////////////////////////////


$deal_won_yamarket_dbs = [];

for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5038" && $deal_all[$i]['UF_CRM_1744364295'] == "5098" && $deal_all[$i]['STAGE_ID'] == "Претензия удовлетв")
	{
	$deal_won_yamarket_dbs[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347']);
	$summ_deal_won_yamarket_dbs += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_won_yamarket_dbs += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_won_yamarket_dbs += $deal_all[$i]['UF_CRM_1744875526'];
	}
}
// все УДОВЛЕТВОРЕННЫЕ претензии Яндекс-Маркет DBS
//echo '<pre>';
//print_r($deal_won_yamarket_dbs);
//echo '</pre>';

////////////////////////////

$deal_won_partly_yamarket_dbs = [];

for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5038" && $deal_all[$i]['UF_CRM_1744364295'] == "5098" && $deal_all[$i]['UF_CRM_1744875443347'] == "5055")
	{
	$deal_won_partly_yamarket_dbs[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347']);
	$summ_deal_won_partly_yamarket_dbs += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_won_partly_yamarket_dbs += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_won_partly_yamarket_dbs += $deal_all[$i]['UF_CRM_1744875526'];
	}
}
// все ЧАСТИЧНО УДОВЛЕТВОРЕННЫЕ претензии Яндекс-Маркет DBS
//echo '<pre>';
//print_r($deal_won_partly_yamarket_dbs);
//echo '</pre>';



////////////////////////////

$deal_lose_yamarket_dbs = [];
for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5038" && $deal_all[$i]['UF_CRM_1744364295'] == "5098" && $deal_all[$i]['STAGE_ID'] == "Претензия не удовлетв")
	{
	$deal_lose_yamarket_dbs[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347']);
	$summ_deal_lose_yamarket_dbs += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_lose_yamarket_dbs += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_lose_yamarket_dbs += $deal_all[$i]['UF_CRM_1744875526'];
	}
}
// все НЕ УДОВЛЕТВОРЕННЫЕ претензии Яндекс-Маркет DBS
//echo '<pre>';
//print_r($deal_lose_yamarket_dbs);
//echo '</pre>';




/////////////////////////////////
/////Яндекс-Маркет FBS //////////////////////
///////////////////////////////


$deal_all_yamarket_fbs = [];

for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5038" && $deal_all[$i]['UF_CRM_1744364295'] == "5039")
	{
	$print_title = $deal_all[$i]['TITLE'];
	$deal_all_yamarket_fbs[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "REAL_OPPORTUNITY" => $deal_all[$i]['UF_CRM_1748263562854'], "COMPENS_OPPORTUNITY" => $deal_all[$i]['UF_CRM_1744875526'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347'],
	"LINK" => "<a target='_blank' href=https://b24.topcomputer.ru/crm/deal/details/".$deal_all[$i]['ID']."/>".$print_title."</a>", "CELL_COLOR" => $deal_all[$i]['CELL_COLOR']);
	$summ_deal_all_yamarket_fbs += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_all_yamarket_fbs += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_all_yamarket_fbs += $deal_all[$i]['UF_CRM_1744875526'];
	$summ_time_deal_all_yamarket_fbs += $deal_all[$i]['UF_CRM_1749011297'];
	$summ_time_deal_all_yamarket_fbs_average = $summ_time_deal_all_yamarket_fbs/count($deal_all_yamarket_fbs);
	}
}

for ($i=0; $i<count($deal_all_yamarket_fbs); $i++)
{
	$price = $deal_all_yamarket_fbs[$i]['REAL_OPPORTUNITY'];

	$str = strpos($price, "|");
	$row = substr($price, 0, $str);

	$price_compens = $deal_all_yamarket_fbs[$i]['COMPENS_OPPORTUNITY'];

	$str_compens = strpos($price_compens, "|");
	$row_compens = substr($price_compens, 0, $str_compens);

	$link_deal_all_yamarket_fbs[] = array("LINK" => $deal_all_yamarket_fbs[$i]['LINK'], "STAGE" => $deal_all_yamarket_fbs[$i]['STAGE_ID'], "SUMM" =>$deal_all_yamarket_fbs[$i]['OPPORTUNITY'], "REAL_SUMM" => $row, "COMPENS_SUMM" => $row_compens, "CELL_COLOR" => $deal_all_yamarket_fbs[$i]['CELL_COLOR']);
}



// все претензии Яндекс-Маркет FBS
//echo '<pre>';
//print_r($deal_all_yamarket_fbs);
//echo '</pre>';

////////////////////////////

$deal_new_yamarket_fbs = [];

for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5038" && $deal_all[$i]['UF_CRM_1744364295'] == "5039" && $deal_all[$i]['STAGE_ID'] == "Новая претензия")
	{
	$deal_new_yamarket_fbs[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347']);
	$summ_deal_new_yamarket_fbs += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_new_yamarket_fbs += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_new_yamarket_fbs += $deal_all[$i]['UF_CRM_1744875526'];
	}
}

// все НОВЫЕ претензии Яндекс-Маркет FBS
//echo '<pre>';
//print_r($deal_new_yamarket_fbs);
//echo '</pre>';

////////////////////////////


$deal_get_yamarket_fbs = [];

for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5038" && $deal_all[$i]['UF_CRM_1744364295'] == "5039" && $deal_all[$i]['STAGE_ID'] == "Подача претензии")
	{
	$deal_get_yamarket_fbs[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347']);
	$summ_deal_get_yamarket_fbs += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_get_yamarket_fbs += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_get_yamarket_fbs += $deal_all[$i]['UF_CRM_1744875526'];
	}
}

// Подача претензии Яндекс-Маркет FBS
//echo '<pre>';
//print_r($deal_get_yamarket_fbs);
//echo '</pre>';

////////////////////////////


$deal_answer_yamarket_fbs = [];

for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5038" && $deal_all[$i]['UF_CRM_1744364295'] == "5039" && $deal_all[$i]['STAGE_ID'] == "Ответ по претензии")
	{
	$deal_answer_yamarket_fbs[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347']);
	$summ_deal_answer_yamarket_fbs += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_answer_yamarket_fbs += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_answer_yamarket_fbs += $deal_all[$i]['UF_CRM_1744875526'];
	}
}

// Ответ по претензии Яндекс-Маркет FBS
//echo '<pre>';
//print_r($deal_get_yamarket_fbs);
//echo '</pre>';

////////////////////////////

$deal_preparation_yamarket_fbs = [];

for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5038" && $deal_all[$i]['UF_CRM_1744364295'] == "5039" && $deal_all[$i]['STAGE_ID'] == "Объединение товаров")
	{
	$deal_preparation_yamarket_fbs[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347']);
	$summ_deal_preparation_yamarket_fbs += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_preparation_yamarket_fbs += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_preparation_yamarket_fbs += $deal_all[$i]['UF_CRM_1744875526'];
	}
}

// Подготовка к экспертизе Яндекс-Маркет FBS
//echo '<pre>';
//print_r($deal_get_yamarket_fbs);
//echo '</pre>';

////////////////////////////

$deal_won_yamarket_fbs = [];

for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5038" && $deal_all[$i]['UF_CRM_1744364295'] == "5039" && $deal_all[$i]['STAGE_ID'] == "Претензия удовлетв")
	{
	$deal_won_yamarket_fbs[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347']);
	$summ_deal_won_yamarket_fbs += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_won_yamarket_fbs += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_won_yamarket_fbs += $deal_all[$i]['UF_CRM_1744875526'];
	}
}
// все УДОВЛЕТВОРЕННЫЕ претензии Яндекс-Маркет FBS
//echo '<pre>';
//print_r($deal_won_yamarket_fbs);
//echo '</pre>';

////////////////////////////

$deal_won_partly_yamarket_fbs = [];

for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5038" && $deal_all[$i]['UF_CRM_1744364295'] == "5039" && $deal_all[$i]['UF_CRM_1744875443347'] == "5055")
	{
	$deal_won_partly_yamarket_fbs[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347']);
	$summ_deal_won_partly_yamarket_fbs += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_won_partly_yamarket_fbs += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_won_partly_yamarket_fbs += $deal_all[$i]['UF_CRM_1744875526'];
	}
}
// все ЧАСТИЧНО УДОВЛЕТВОРЕННЫЕ претензии Яндекс-Маркет FBS
//echo '<pre>';
//print_r($deal_won_partly_yamarket_fbs);
//echo '</pre>';



////////////////////////////

$deal_lose_yamarket_fbs = [];
for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5038" && $deal_all[$i]['UF_CRM_1744364295'] == "5039" && $deal_all[$i]['STAGE_ID'] == "Претензия не удовлетв")
	{
	$deal_lose_yamarket_fbs[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347']);
	$summ_deal_lose_yamarket_fbs += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_lose_yamarket_fbs += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_lose_yamarket_fbs += $deal_all[$i]['UF_CRM_1744875526'];
	}
}
// все НЕ УДОВЛЕТВОРЕННЫЕ претензии Яндекс-Маркет FBS
//echo '<pre>';
//print_r($deal_lose_yamarket_fbs);
//echo '</pre>';



/////////////////////////////////
/////Яндекс-Маркет FBY //////////////////////
///////////////////////////////


$deal_all_yamarket_fby = [];

for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5038" && $deal_all[$i]['UF_CRM_1744364295'] == "5099")
	{
	$print_title = $deal_all[$i]['TITLE'];
	$deal_all_yamarket_fby[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "REAL_OPPORTUNITY" => $deal_all[$i]['UF_CRM_1748263562854'], "COMPENS_OPPORTUNITY" => $deal_all[$i]['UF_CRM_1744875526'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347'],
	"LINK" => "<a target='_blank' href=https://b24.topcomputer.ru/crm/deal/details/".$deal_all[$i]['ID']."/>".$print_title."</a>", "CELL_COLOR" => $deal_all[$i]['CELL_COLOR']);
	$summ_deal_all_yamarket_fby += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_all_yamarket_fby += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_all_yamarket_fby += $deal_all[$i]['UF_CRM_1744875526'];
	$summ_time_deal_all_yamarket_fby += $deal_all[$i]['UF_CRM_1749011297'];
	$summ_time_deal_all_yamarket_fby_average = $summ_time_deal_all_yamarket_fby/count($deal_all_yamarket_fby);
	}
}

for ($i=0; $i<count($deal_all_yamarket_fby); $i++)
{
	$price = $deal_all_yamarket_fby[$i]['REAL_OPPORTUNITY'];

	$str = strpos($price, "|");
	$row = substr($price, 0, $str);

	$price_compens = $deal_all_yamarket_fby[$i]['COMPENS_OPPORTUNITY'];

	$str_compens = strpos($price_compens, "|");
	$row_compens = substr($price_compens, 0, $str_compens);

	$link_deal_all_yamarket_fby[] = array("LINK" => $deal_all_yamarket_fby[$i]['LINK'], "STAGE" => $deal_all_yamarket_fby[$i]['STAGE_ID'], "SUMM" =>$deal_all_yamarket_fby[$i]['OPPORTUNITY'], "REAL_SUMM" => $row,"COMPENS_SUMM" => $row_compens,  "CELL_COLOR" => $deal_all_yamarket_fby[$i]['CELL_COLOR']);
}



// все претензии Яндекс-Маркет FBY
//echo '<pre>';
//print_r($deal_all_yamarket_fby);
//echo '</pre>';

////////////////////////////

$deal_new_yamarket_fby = [];

for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5038" && $deal_all[$i]['UF_CRM_1744364295'] == "5099" && $deal_all[$i]['STAGE_ID'] == "Новая претензия")
	{
	$deal_new_yamarket_fby[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347']);
	$summ_deal_new_yamarket_fby += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_new_yamarket_fby += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_new_yamarket_fby += $deal_all[$i]['UF_CRM_1744875526'];
	}
}
// все НОВЫЕ претензии Яндекс-Маркет FBY
//echo '<pre>';
//print_r($deal_new_yamarket_fby);
//echo '</pre>';

////////////////////////////


$deal_get_yamarket_fby = [];

for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5038" && $deal_all[$i]['UF_CRM_1744364295'] == "5099" && $deal_all[$i]['STAGE_ID'] == "Подача претензии")
	{
	$deal_get_yamarket_fby[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347']);
	$summ_deal_get_yamarket_fby += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_get_yamarket_fby += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_get_yamarket_fby += $deal_all[$i]['UF_CRM_1744875526'];
	}
}
// Подача претензии Яндекс-Маркет FBY
//echo '<pre>';
//print_r($deal_new_yamarket_fby);
//echo '</pre>';

////////////////////////////


$deal_answer_yamarket_fby = [];

for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5038" && $deal_all[$i]['UF_CRM_1744364295'] == "5099" && $deal_all[$i]['STAGE_ID'] == "Ответ по претензии")
	{
	$deal_answer_yamarket_fby[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347']);
	$summ_deal_answer_yamarket_fby += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_answer_yamarket_fby += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_answer_yamarket_fby += $deal_all[$i]['UF_CRM_1744875526'];
	}
}
// Ответ по претензии Яндекс-Маркет FBY
//echo '<pre>';
//print_r($deal_new_yamarket_fby);
//echo '</pre>';

////////////////////////////


$deal_preparation_yamarket_fby = [];

for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5038" && $deal_all[$i]['UF_CRM_1744364295'] == "5099" && $deal_all[$i]['STAGE_ID'] == "Объединение товаров")
	{
	$deal_preparation_yamarket_fby[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347']);
	$summ_deal_preparation_yamarket_fby += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_preparation_yamarket_fby += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_preparation_yamarket_fby += $deal_all[$i]['UF_CRM_1744875526'];
	}
}
// Ответ по претензии Яндекс-Маркет FBY
//echo '<pre>';
//print_r($deal_new_yamarket_fby);
//echo '</pre>';

////////////////////////////


$deal_won_yamarket_fby = [];

for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5038" && $deal_all[$i]['UF_CRM_1744364295'] == "5099" && $deal_all[$i]['STAGE_ID'] == "Претензия удовлетв")
	{
	$deal_won_yamarket_fby[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347']);
	$summ_deal_won_yamarket_fby += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_won_yamarket_fby += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_won_yamarket_fby += $deal_all[$i]['UF_CRM_1744875526'];
	}
}
// все УДОВЛЕТВОРЕННЫЕ претензии Яндекс-Маркет FBY
//echo '<pre>';
//print_r($deal_won_yamarket_fby);
//echo '</pre>';

////////////////////////////

$deal_won_partly_yamarket_fby = [];

for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5038" && $deal_all[$i]['UF_CRM_1744364295'] == "5099" && $deal_all[$i]['UF_CRM_1744875443347'] == "5055")
	{
	$deal_won_partly_yamarket_fby[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347']);
	$summ_deal_won_partly_yamarket_fby += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_won_partly_yamarket_fby += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_won_partly_yamarket_fby += $deal_all[$i]['UF_CRM_1744875526'];
	}
}
// все ЧАСТИЧНО УДОВЛЕТВОРЕННЫЕ претензии Яндекс-Маркет FBY
//echo '<pre>';
//print_r($deal_won_partly_yamarket_fby);
//echo '</pre>';



////////////////////////////

$deal_lose_yamarket_fby = [];
for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5038" && $deal_all[$i]['UF_CRM_1744364295'] == "5099" && $deal_all[$i]['STAGE_ID'] == "Претензия не удовлетв")
	{
	$deal_lose_yamarket_fby[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347']);
	$summ_deal_lose_yamarket_fby += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_lose_yamarket_fby += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_lose_yamarket_fby += $deal_all[$i]['UF_CRM_1744875526'];
	}
}
// все НЕ УДОВЛЕТВОРЕННЫЕ претензии Яндекс-Маркет FBY
//echo '<pre>';
//print_r($deal_lose_yamarket_fby);
//echo '</pre>';




/////////////////////////////////
/////Яндекс-Маркет FBO //////////////////////
///////////////////////////////


$deal_all_yamarket_fbo = [];

for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5038" && $deal_all[$i]['UF_CRM_1744364295'] == "5040")
	{
	$print_title = $deal_all[$i]['TITLE'];
	$deal_all_yamarket_fbo[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "REAL_OPPORTUNITY" => $deal_all[$i]['UF_CRM_1748263562854'], "COMPENS_OPPORTUNITY" => $deal_all[$i]['UF_CRM_1744875526'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347'],
	"LINK" => "<a target='_blank' href=https://b24.topcomputer.ru/crm/deal/details/".$deal_all[$i]['ID']."/>".$print_title."</a>", "CELL_COLOR" => $deal_all[$i]['CELL_COLOR']);
	$summ_deal_all_yamarket_fbo += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_all_yamarket_fbo += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_all_yamarket_fbo += $deal_all[$i]['UF_CRM_1744875526'];
	$summ_time_deal_all_yamarket_fbo += $deal_all[$i]['UF_CRM_1749011297'];
	$summ_time_deal_all_yamarket_fbo_average = $summ_time_deal_all_yamarket_fbo/count($deal_all_yamarket_fbo);

	}
}

for ($i=0; $i<count($deal_all_yamarket_fbo); $i++)
{
	$price = $deal_all_yamarket_fbo[$i]['REAL_OPPORTUNITY'];

	$str = strpos($price, "|");
	$row = substr($price, 0, $str);

	$price_compens = $deal_all_yamarket_fbo[$i]['COMPENS_OPPORTUNITY'];

	$str_compens = strpos($price_compens, "|");
	$row_compens = substr($price_compens, 0, $str_compens);

	$link_deal_all_yamarket_fbo[] = array("LINK" => $deal_all_yamarket_fbo[$i]['LINK'], "STAGE" => $deal_all_yamarket_fbo[$i]['STAGE_ID'], "SUMM" =>$deal_all_yamarket_fbo[$i]['OPPORTUNITY'], "REAL_SUMM" => $row, "COMPENS_SUMM" => $row_compens, "CELL_COLOR" => $deal_all_yamarket_fbo[$i]['CELL_COLOR']);
}



// все претензии Яндекс-Маркет FBO
//echo '<pre>';
//print_r($deal_all_yamarket_fbo);
//echo '</pre>';

////////////////////////////

$deal_new_yamarket_fbo = [];

for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5038" && $deal_all[$i]['UF_CRM_1744364295'] == "5040" && $deal_all[$i]['STAGE_ID'] == "Новая претензия")
	{
	$deal_new_yamarket_fbo[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347']);
	$summ_deal_new_yamarket_fbo += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_new_yamarket_fbo += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_new_yamarket_fbo += $deal_all[$i]['UF_CRM_1744875526'];
	}
}
// все НОВЫЕ претензии Яндекс-Маркет FBO
//echo '<pre>';
//print_r($deal_new_yamarket_fbo);
//echo '</pre>';

////////////////////////////


$deal_get_yamarket_fbo = [];

for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5038" && $deal_all[$i]['UF_CRM_1744364295'] == "5040" && $deal_all[$i]['STAGE_ID'] == "Подача претензии")
	{
	$deal_get_yamarket_fbo[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347']);
	$summ_deal_get_yamarket_fbo += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_get_yamarket_fbo += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_get_yamarket_fbo += $deal_all[$i]['UF_CRM_1744875526'];
	}
}
// Подача претензии Яндекс-Маркет FBO
//echo '<pre>';
//print_r($deal_new_yamarket_fbo);
//echo '</pre>';

////////////////////////////


$deal_answer_yamarket_fbo = [];

for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5038" && $deal_all[$i]['UF_CRM_1744364295'] == "5040" && $deal_all[$i]['STAGE_ID'] == "Ответ по экспертизе")
	{
	$deal_answer_yamarket_fbo[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347']);
	$summ_deal_answer_yamarket_fbo += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_answer_yamarket_fbo += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_answer_yamarket_fbo += $deal_all[$i]['UF_CRM_1744875526'];
	}
}
// Ответ по претензии Яндекс-Маркет FBO
//echo '<pre>';
//print_r($deal_new_yamarket_fbo);
//echo '</pre>';

////////////////////////////


$deal_preparation_yamarket_fbo = [];

for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5038" && $deal_all[$i]['UF_CRM_1744364295'] == "5040" && $deal_all[$i]['STAGE_ID'] == "Объединение товаров")
	{
	$deal_preparation_yamarket_fbo[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347']);
	$summ_deal_preparation_yamarket_fbo += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_preparation_yamarket_fbo += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_preparation_yamarket_fbo += $deal_all[$i]['UF_CRM_1744875526'];
	}
}
// Ответ по претензии Яндекс-Маркет FBO
//echo '<pre>';
//print_r($deal_new_yamarket_fbo);
//echo '</pre>';

////////////////////////////


$deal_won_yamarket_fbo = [];

for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5038" && $deal_all[$i]['UF_CRM_1744364295'] == "5040" && $deal_all[$i]['STAGE_ID'] == "Претензия удовлетв")
	{
	$deal_won_yamarket_fbo[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347']);
	$summ_deal_won_yamarket_fbo += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_won_yamarket_fbo += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_won_yamarket_fbo += $deal_all[$i]['UF_CRM_1744875526'];
	}
}
// все УДОВЛЕТВОРЕННЫЕ претензии Яндекс-Маркет FBO
//echo '<pre>';
//print_r($deal_won_yamarket_fbo);
//echo '</pre>';

////////////////////////////

$deal_won_partly_yamarket_fbo = [];

for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5038" && $deal_all[$i]['UF_CRM_1744364295'] == "5040" && $deal_all[$i]['UF_CRM_1744875443347'] == "5055")
	{
	$deal_won_partly_yamarket_fbo[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347']);
	$summ_deal_won_partly_yamarket_fbo += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_won_partly_yamarket_fbo += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_won_partly_yamarket_fbo += $deal_all[$i]['UF_CRM_1744875526'];
	}
}
// все ЧАСТИЧНО УДОВЛЕТВОРЕННЫЕ претензии Яндекс-Маркет FBO
//echo '<pre>';
//print_r($deal_won_partly_yamarket_fbo);
//echo '</pre>';



////////////////////////////

$deal_lose_yamarket_fbo = [];
for ($i=0; $i<count($deal_all); $i++)
{
	if($deal_all[$i]['UF_CRM_1744364236'] == "5038" && $deal_all[$i]['UF_CRM_1744364295'] == "5040" && $deal_all[$i]['STAGE_ID'] == "Претензия не удовлетв")
	{
	$deal_lose_yamarket_fbo[] = array("ID" => $deal_all[$i]['ID'], "TITLE" => $deal_all[$i]['TITLE'], "STAGE_ID" => $deal_all[$i]['STAGE_ID'], "OPPORTUNITY" => $deal_all[$i]['OPPORTUNITY'], "MARKET_PLAYS" => $deal_all[$i]['UF_CRM_1744364236'], "WORK_MODEL" => $deal_all[$i]['UF_CRM_1744364295'], "RESPONSE_CLAIM" => $deal_all[$i]['UF_CRM_1744875443347']);
	$summ_deal_lose_yamarket_fbo += $deal_all[$i]['OPPORTUNITY'];
	$summ_real_deal_lose_yamarket_fbo += $deal_all[$i]['UF_CRM_1748263562854'];
	$summ_compens_deal_lose_yamarket_fbo += $deal_all[$i]['UF_CRM_1744875526'];
	}
}
// все НЕ УДОВЛЕТВОРЕННЫЕ претензии Яндекс-Маркет FBO
//echo '<pre>';
//print_r($deal_lose_yamarket_fbo);
//echo '</pre>';


?>


<H2>[Юр Отдел] Претензии МП </H2>

<style> 
		body { 
			font-family: Arial, sans-serif; 
			margin: 0; 
			padding: 0; 
		} 

		.table-container { 
			display: flex; 
			justify-content: space-between; 
			margin: 5px; 
		} 

		.table { 
			border-collapse: collapse; 
			width: 330%; 
			height:330%;
			margin-right: 5px; 
			font-size: x-small;
			padding: 10px 15px;
		} 
		th, 
		td { 
			border: 1px solid #ddd; 
			padding: 2px; 
			text-align: center; 
		} 

		th { 
			background-color: #f2f2f2; 
		} 
	</style> 
<form method="get">
	<table>
<tr><td class="form">
<input type="date" name="date" value="<?php echo $date;?>">
<input type="date" name="datenow" value="<?php echo $datenow;?>">
      <button type="submit">Показать</button>



<?php
	if (isset($_GET)) {
		$_GET['date'];
		$_GET['datenow'];
	}
?>
	</td>
	<td width = "78%" style="text-align: right; border: none;"><h3><a href="https://b24.topcomputer.ru/local/report/legal_depart/legal-department-claims-MP-print.php?<?php echo "date=".$date ;?>&<?php echo "datenow=".$datenow; ?>"> Скачать в Excel </a></h3>
</td>
</tr>
</table>
    </form>

<div class="table-container"> 

		<table class="table"> 

		<tr>
			<th width="1000"><b>OZON FBS</b></th>
			<th>Кол-во</th>
			<th>Заявл сумма</th>
			<th>Реал сумма</th>
			<th>Компенс</th>
		</tr>
		<tr>
			<td style="text-align: right;">Претензий всего</td>
			<td style="text-align: center;"><?php echo count($deal_all_ozon_fbs);?></td>
			<td style="text-align: center; white-space: nowrap;"><?php echo number_format($summ_deal_all_ozon_fbs, 0, ',', ' ');?></td>
			<td style="text-align: center; white-space: nowrap;"><?php echo number_format($summ_real_deal_all_ozon_fbs, 0, ',', ' ');?></td>
			<td style="text-align: center; white-space: nowrap;"><?php echo number_format($summ_compens_deal_all_ozon_fbs, 0, ',', ' ');?></td>
		</tr>
			<tr><th colspan="5">Lead Time: <?php echo number_format($summ_time_deal_all_ozon_fbs_average, 0, ',', ' ')." мин";?></th></tr>
		<tr>
			<td style="text-align: right;">Новых претензий</td>
			<td style="text-align: center;"><?php echo count($deal_new_ozon_fbs);?></td>
			<td style="text-align: center;"><?php echo number_format($summ_deal_new_ozon_fbs, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_real_deal_new_ozon_fbs, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_compens_deal_new_ozon_fbs, 0, ',', ' ');?></td>

		</tr>
		<tr>
			<td style="text-align: right;">Подача претензии</td>
			<td style="text-align: center;"><?php echo count($deal_get_ozon_fbs);?></td>
			<td style="text-align: center;"><?php echo number_format($summ_deal_get_ozon_fbs, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_real_deal_get_ozon_fbs, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_compens_deal_get_ozon_fbs, 0, ',', ' ');?></td>
		</tr>
		<tr>
			<td style="text-align: right;">Ответ по претензии</td>
			<td style="text-align: center;"><?php echo count($deal_answer_ozon_fbs);?></td>
			<td style="text-align: center;"><?php echo number_format($summ_deal_answer_ozon_fbs, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_real_deal_answer_ozon_fbs, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_compens_deal_answer_ozon_fbs, 0, ',', ' ');?></td>
		</tr>
		<tr>
			<td style="text-align: right;">Объед. товаров</td>
			<td style="text-align: center;"><?php echo count($deal_preparation_ozon_fbs);?></td>
			<td style="text-align: center;"><?php echo number_format($summ_deal_preparation_ozon_fbs, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_real_deal_preparation_ozon_fbs, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_compens_deal_preparation_ozon_fbs, 0, ',', ' ');?></td>
		</tr>
		<tr>
			<td style="text-align: right;">Удовлетворено</td>
			<td style="text-align: center;"><?php echo count($deal_won_ozon_fbs);?></td>
			<td style="text-align: center;"><?php echo number_format($summ_deal_won_ozon_fbs, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_real_deal_won_ozon_fbs, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_compens_deal_won_ozon_fbs, 0, ',', ' ');?></td>
		</tr>
		<tr>
			<td style="text-align: right;">Част. удовлетворено</td>
			<td style="text-align: center;"><?php echo count($deal_won_partly_ozon_fbs);?></td>
			<td style="text-align: center;"><?php echo number_format($summ_deal_won_partly_ozon_fbs, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_real_deal_won_partly_ozon_fbs, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_compens_deal_won_partly_ozon_fbs, 0, ',', ' ');?></td>
		</tr>
		<tr>
			<td style="text-align: right;">Отклонено</td>
			<td style="text-align: center;"><?php echo count($deal_lose_ozon_fbs);?></td>
			<td style="text-align: center;"><?php echo number_format($summ_deal_lose_ozon_fbs, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_real_deal_lose_ozon_fbs, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_compens_deal_lose_ozon_fbs, 0, ',', ' ');?></td>
		</tr>
		<tr>
			<th>Название</th>
			<th>Стадия</th>
			<th></th>
			<th></th>
			<th></th>
		</tr>

			<?php if (!$link_deal_all_ozon_fbs['TOTAL'] = "0") { for ($i=0; $i+1 < count($link_deal_all_ozon_fbs); $i++):?>
	<tr>
			<td style="font-size: 0.5rem; text-align: left;"><?php echo $link_deal_all_ozon_fbs[$i]['LINK'];?></td>
			<td style="font-size: x-small; background: <?php echo $link_deal_all_ozon_fbs[$i]['CELL_COLOR'];?>"><?php echo $link_deal_all_ozon_fbs[$i]['STAGE'];?></td>

<?php if (strpos($link_deal_all_ozon_fbs[$i]['LINK'], 'ЯМ') || strpos($link_deal_all_ozon_fbs[$i]['LINK'], 'OZON') !== false):?>
<td><a target="_blank" href="https://b24.topcomputer.ru/local/report/legal_depart/legal-department-claims-MP-more.php?id=<?php echo $deal_all_ozon_fbs[$i]['ID']?>"><?php echo number_format($link_deal_all_ozon_fbs[$i]['SUMM'], 0, ',', ' ');?></a></td>
<td><a target="_blank" href="https://b24.topcomputer.ru/local/report/legal_depart/legal-department-claims-MP-more.php?id=<?php echo $deal_all_ozon_fbs[$i]['ID']?>"><?php echo number_format($link_deal_all_ozon_fbs[$i]['REAL_SUMM'], 0, ',', ' ');?></a></td>
<td><a target="_blank" href="https://b24.topcomputer.ru/local/report/legal_depart/legal-department-claims-MP-more.php?id=<?php echo $deal_all_ozon_fbs[$i]['ID']?>"><?php echo number_format($link_deal_all_ozon_fbs[$i]['COMPENS_SUMM'], 0, ',', ' ');?></a></td>
<?php else: ?>
<td><?php echo number_format($link_deal_all_ozon_fbs[$i]['SUMM'], 0, ',', ' ');?></td>
<td>
<?php if ($link_deal_all_ozon_fbs[$i]['REAL_SUMM']>0) { echo number_format($link_deal_all_ozon_fbs[$i]['REAL_SUMM'], 0, ',', ' ');?>
<?php } else {echo "0";}?>
</td>
<td>
<?php if ($link_deal_all_ozon_fbs[$i]['COMPENS_SUMM']>0) { echo number_format($link_deal_all_ozon_fbs[$i]['COMPENS_SUMM'], 0, ',', ' ');?>
<?php } else {echo "0";}?>
</td>
<?php endif;?>





	<?php endfor; ?>
		<?php } ?>
		</tr>
</table>



		<table class="table"> 

		<tr>
			<th><b>OZON FBO</b></th>
			<th>Кол-во</th>
			<th>Заявл сумма</th>
			<th>Реал сумма</th>
			<th>Компенс</th>
		</tr>
		<tr>
			<td style="text-align: right;">Претензий всего</td>
			<td style="text-align: center;"><?php echo count($deal_all_ozon_fbo);?></td>
			<td style="text-align: center; white-space: nowrap;"><?php echo number_format($summ_deal_all_ozon_fbo, 0, ',', ' ');?></td>
			<td style="text-align: center; white-space: nowrap;"><?php echo number_format($summ_real_deal_all_ozon_fbo, 0, ',', ' ');?></td>
			<td style="text-align: center; white-space: nowrap;"><?php echo number_format($summ_compens_deal_all_ozon_fbo, 0, ',', ' ');?></td>

		</tr>
	<tr><th colspan="5">Lead Time: <?php echo number_format($summ_time_deal_all_ozon_fbo_average, 0, ',', ' ')." мин";?></th></tr>
		<tr>
			<td style="text-align: right;">Новых претензий</td>
			<td style="text-align: center;"><?php echo count($deal_new_ozon_fbo);?></td>
			<td style="text-align: center;"><?php echo number_format($summ_deal_new_ozon_fbo, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_real_deal_new_ozon_fbo, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_compens_deal_new_ozon_fbo, 0, ',', ' ');?></td>
		</tr>
		<tr>
			<td style="text-align: right;">Подача претензии</td>
			<td style="text-align: center;"><?php echo count($deal_get_ozon_fbo);?></td>
			<td style="text-align: center;"><?php echo number_format($summ_deal_get_ozon_fbo, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_real_deal_get_ozon_fbo, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_compens_deal_get_ozon_fbo, 0, ',', ' ');?></td>
		</tr>
		<tr>
			<td style="text-align: right;">Ответ по претензии</td>
			<td style="text-align: center;"><?php echo count($deal_answer_ozon_fbo);?></td>
			<td style="text-align: center;"><?php echo number_format($summ_deal_answer_ozon_fbo, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_real_deal_answer_ozon_fbo, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_compens_deal_answer_ozon_fbo, 0, ',', ' ');?></td>
		</tr>
		<tr>
			<td style="text-align: right;">Объед. товаров</td>
			<td style="text-align: center;"><?php echo count($deal_preparation_ozon_fbo);?></td>
			<td style="text-align: center;"><?php echo number_format($summ_deal_preparation_ozon_fbo, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_real_deal_preparation_ozon_fbo, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_compens_deal_preparation_ozon_fbo, 0, ',', ' ');?></td>
		</tr>
		<tr>
			<td style="text-align: right;">Удовлетворено</td>
			<td style="text-align: center;"><?php echo count($deal_won_ozon_fbo);?></td>
			<td style="text-align: center;"><?php echo number_format($summ_deal_won_ozon_fbo, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_real_deal_won_ozon_fbo, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_compens_deal_won_ozon_fbo, 0, ',', ' ');?></td>
		</tr>
		<tr>
			<td style="text-align: right;">Част. удовлетворено</td>
			<td style="text-align: center;"><?php echo count($deal_won_partly_ozon_fbo);?></td>
			<td style="text-align: center;"><?php echo number_format($summ_deal_won_partly_ozon_fbo, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_real_deal_won_partly_ozon_fbo, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_compens_deal_won_partly_ozon_fbo, 0, ',', ' ');?></td>
		</tr>
		<tr>
			<td style="text-align: right;">Отклонено</td>
			<td style="text-align: center;"><?php echo count($deal_lose_ozon_fbo);?></td>
			<td style="text-align: center;"><?php echo number_format($summ_deal_lose_ozon_fbo, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_real_deal_lose_ozon_fbo, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_compens_deal_lose_ozon_fbo, 0, ',', ' ');?></td>
		</tr>
		<tr>
			<th>Название</th>
			<th>Стадия</th>
			<th></th>
			<th></th>
			<th></th>
		</tr>
<?php if (!$link_deal_all_ozon_fbo['TOTAL'] = "0") { for ($i=0; $i+1 < count($link_deal_all_ozon_fbo); $i++):?>
	<tr>
			<td style="font-size: 0.5rem; text-align: left;"><?php echo $link_deal_all_ozon_fbo[$i]['LINK'];?></td>
			<td style="font-size: x-small; background: <?php echo $link_deal_all_ozon_fbo[$i]['CELL_COLOR'];?>"><?php echo $link_deal_all_ozon_fbo[$i]['STAGE'];?></td>

<?php if (strpos($link_deal_all_ozon_fbo[$i]['LINK'], 'ЯМ') || strpos($link_deal_all_ozon_fbo[$i]['LINK'], 'OZON') !== false):?>
<td><a target="_blank" href="https://b24.topcomputer.ru/local/report/legal_depart/legal-department-claims-MP-more.php?id=<?php echo $deal_all_ozon_fbo[$i]['ID']?>"><?php echo number_format($link_deal_all_ozon_fbo[$i]['SUMM'], 0, ',', ' ');?></a></td>
<td><a target="_blank" href="https://b24.topcomputer.ru/local/report/legal_depart/legal-department-claims-MP-more.php?id=<?php echo $deal_all_ozon_fbo[$i]['ID']?>"><?php echo number_format($link_deal_all_ozon_fbo[$i]['REAL_SUMM'], 0, ',', ' ');?></a></td>
<td><a target="_blank" href="https://b24.topcomputer.ru/local/report/legal_depart/legal-department-claims-MP-more.php?id=<?php echo $deal_all_ozon_fbo[$i]['ID']?>"><?php echo number_format($link_deal_all_ozon_fbo[$i]['COMPENS_SUMM'], 0, ',', ' ');?></a></td>
<?php else: ?>
<td><?php echo number_format($link_deal_all_ozon_fbo[$i]['SUMM'], 0, ',', ' ');?></td>
<td>
<?php if ($link_deal_all_ozon_fbo[$i]['REAL_SUMM']>0) { echo number_format($link_deal_all_ozon_fbo[$i]['REAL_SUMM'], 0, ',', ' ');?>
<?php } else {echo "0";}?>
</td>
<td>
<?php if ($link_deal_all_ozon_fbo[$i]['COMPENS_SUMM']>0) { echo number_format($link_deal_all_ozon_fbo[$i]['COMPENS_SUMM'], 0, ',', ' ');?>
<?php } else {echo "0";}?>
</td>
<?php endif;?>

		<?php endfor; ?>
		<?php } ?>
		</tr>
</table>



		<table class="table"> 
		<tr>
			<th><b>Я.Маркет DBS</b></th>
			<th>Кол-во</th>
			<th>Заявл сумма</th>
			<th>Реал сумма</th>
			<th>Компенс</th>
		</tr>
		<tr>
			<td style="text-align: right;">Претензий всего</td>
			<td style="text-align: center;"><?php echo count($deal_all_yamarket_dbs);?></td>
			<td style="text-align: center; white-space: nowrap;"><?php echo number_format($summ_deal_all_yamarket_dbs, 0, ',', ' ');?></td>
			<td style="text-align: center; white-space: nowrap;"><?php echo number_format($summ_real_deal_all_yamarket_dbs, 0, ',', ' ');?></td>
			<td style="text-align: center; white-space: nowrap;"><?php echo number_format($summ_compens_deal_all_yamarket_dbs, 0, ',', ' ');?></td>
		</tr>
	<tr><th colspan="5">Lead Time: <?php echo number_format($summ_time_deal_all_yamarket_dbs_average, 0, ',', ' ')." мин";?></th></tr>
		<tr>
			<td style="text-align: right;">Новых претензий</td>
			<td style="text-align: center;"><?php echo count($deal_new_yamarket_dbs);?></td>
			<td style="text-align: center;"><?php echo number_format($summ_deal_new_yamarket_dbs, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_real_deal_new_yamarket_dbs, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_compens_deal_new_yamarket_dbs, 0, ',', ' ');?></td>
		</tr>
		<tr>
			<td style="text-align: right;">Подача претензии</td>
			<td style="text-align: center;"><?php echo count($deal_get_yamarket_dbs);?></td>
			<td style="text-align: center;"><?php echo number_format($summ_deal_get_yamarket_dbs, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_real_deal_get_yamarket_dbs, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_compens_deal_get_yamarket_dbs, 0, ',', ' ');?></td>
		</tr>
		<tr>
			<td style="text-align: right;">Ответ по претензии</td>
			<td style="text-align: center;"><?php echo count($deal_answer_yamarket_dbs);?></td>
			<td style="text-align: center;"><?php echo number_format($summ_deal_answer_yamarket_dbs, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_real_deal_answer_yamarket_dbs, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_compens_deal_answer_yamarket_dbs, 0, ',', ' ');?></td>
		</tr>
		<tr>
			<td style="text-align: right;">Объед. товаров</td>
			<td style="text-align: center;"><?php echo count($deal_preparation_yamarket_dbs);?></td>
			<td style="text-align: center;"><?php echo number_format($summ_deal_preparation_yamarket_dbs, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_real_deal_preparation_yamarket_dbs, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_compens_deal_preparation_yamarket_dbs, 0, ',', ' ');?></td>
		</tr>
		<tr>
			<td style="text-align: right;">Удовлетворено</td>
			<td style="text-align: center;"><?php echo count($deal_won_yamarket_dbs);?></td>
			<td style="text-align: center;"><?php echo number_format($summ_deal_won_yamarket_dbs, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_real_deal_won_yamarket_dbs, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_compens_deal_won_yamarket_dbs, 0, ',', ' ');?></td>
		</tr>
		<tr>
			<td style="text-align: right;">Част. удовлетворено</td>
			<td style="text-align: center;"><?php echo count($deal_won_partly_yamarket_dbs);?></td>
			<td style="text-align: center;"><?php echo number_format($summ_deal_won_partly_yamarket_dbs, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_real_deal_won_partly_yamarket_dbs, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_compens_deal_won_partly_yamarket_dbs, 0, ',', ' ');?></td>
		</tr>
		<tr>
			<td style="text-align: right;">Отклонено</td>
			<td style="text-align: center;"><?php echo count($deal_lose_yamarket_dbs);?></td>
			<td style="text-align: center;"><?php echo number_format($summ_deal_lose_yamarket_dbs, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_real_deal_lose_yamarket_dbs, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_compens_deal_lose_yamarket_dbs, 0, ',', ' ');?></td>
		</tr>
		<tr>
			<th>Название</th>
			<th>Стадия</th>
			<th></th>
			<th></th>
			<th></th>
		</tr>


			<?php if (!$link_deal_all_yamarket_dbs['TOTAL'] = "0" ) { for ($i=0; $i+1 < count($link_deal_all_yamarket_dbs); $i++):?>

	<tr>
			<td style="font-size: 0.5rem; text-align: left;"><?php echo $link_deal_all_yamarket_dbs[$i]['LINK'];?></td>
			<td style="font-size: x-small; background: <?php echo $link_deal_all_yamarket_dbs[$i]['CELL_COLOR'];?>"><?php echo $link_deal_all_yamarket_dbs[$i]['STAGE'];?></td>


<?php if (strpos($link_deal_all_yamarket_dbs[$i]['LINK'], 'ЯМ') || strpos($link_deal_all_yamarket_dbs[$i]['LINK'], 'OZON') !== false):?>
<td><a target="_blank" href="https://b24.topcomputer.ru/local/report/legal_depart/legal-department-claims-MP-more.php?id=<?php echo $deal_all_yamarket_dbs[$i]['ID']?>"><?php echo number_format($link_deal_all_yamarket_dbs[$i]['SUMM'], 0, ',', ' ');?></a></td>
<td><a target="_blank" href="https://b24.topcomputer.ru/local/report/legal_depart/legal-department-claims-MP-more.php?id=<?php echo $deal_all_yamarket_dbs[$i]['ID']?>"><?php echo number_format($link_deal_all_yamarket_dbs[$i]['REAL_SUMM'], 0, ',', ' ');?></a></td>
<td><a target="_blank" href="https://b24.topcomputer.ru/local/report/legal_depart/legal-department-claims-MP-more.php?id=<?php echo $deal_all_yamarket_dbs[$i]['ID']?>"><?php echo number_format($link_deal_all_yamarket_dbs[$i]['COMPENS_SUMM'], 0, ',', ' ');?></a></td>
<?php else: ?>
<td><?php echo number_format($link_deal_all_yamarket_dbs[$i]['SUMM'], 0, ',', ' ');?></td>
<td>
<?php if ($link_deal_all_yamarket_dbs[$i]['REAL_SUMM']>0) { echo number_format($link_deal_all_yamarket_dbs[$i]['REAL_SUMM'], 0, ',', ' ');?>
<?php } else {echo "0";}?>
</td>
<td>
<?php if ($link_deal_all_yamarket_dbs[$i]['COMPENS_SUMM']>0) { echo number_format($link_deal_all_yamarket_dbs[$i]['COMPENS_SUMM'], 0, ',', ' ');?>
<?php } else {echo "0";}?>
</td>
<?php endif;?>

		<?php  endfor;} ?>
		</tr>
</table>


		<table class="table"> 

		<tr>
			<th><b>Я.Маркет FBS</b></th>
			<th>Кол-во</th>
			<th>Заявл сумма</th>
			<th>Реал сумма</th>
			<th>Компенс</th>
		</tr>
		<tr>
			<td style="text-align: right;">Претензий всего</td>
			<td style="text-align: center;"><?php echo count($deal_all_yamarket_fbs);?></td>
			<td style="text-align: center; white-space: nowrap;"><?php echo number_format($summ_deal_all_yamarket_fbs, 0, ',', ' ');?></td>
			<td style="text-align: center; white-space: nowrap;"><?php echo number_format($summ_real_deal_all_yamarket_fbs, 0, ',', ' ');?></td>
			<td style="text-align: center; white-space: nowrap;"><?php echo number_format($summ_compens_deal_all_yamarket_fbs, 0, ',', ' ');?></td>
		</tr>
	<tr><th colspan="5">Lead Time: <?php echo number_format($summ_time_deal_all_yamarket_fbs_average, 0, ',', ' ')." мин";?></th></tr>
		<tr>
			<td style="text-align: right;">Новых претензий</td>
			<td style="text-align: center;"><?php echo count($deal_new_yamarket_fbs);?></td>
			<td style="text-align: center;"><?php echo number_format($summ_deal_new_yamarket_fbs, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_real_deal_new_yamarket_fbs, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_compens_deal_new_yamarket_fbs, 0, ',', ' ');?></td>
		</tr>
		<tr>
			<td style="text-align: right;">Подача претензии</td>
			<td style="text-align: center;"><?php echo count($deal_get_yamarket_fbs);?></td>
			<td style="text-align: center;"><?php echo number_format($summ_deal_get_yamarket_fbs, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_real_deal_get_yamarket_fbs, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_compens_deal_get_yamarket_fbs, 0, ',', ' ');?></td>
		</tr>
		<tr>
			<td style="text-align: right;">Ответ по претензии</td>
			<td style="text-align: center;"><?php echo count($deal_answer_yamarket_fbs);?></td>
			<td style="text-align: center;"><?php echo number_format($summ_deal_answer_yamarket_fbs, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_real_deal_answer_yamarket_fbs, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_compens_deal_answer_yamarket_fbs, 0, ',', ' ');?></td>
		</tr>
		<tr>
			<td style="text-align: right;">Объед. товаров</td>
			<td style="text-align: center;"><?php echo count($deal_preparation_yamarket_fbs);?></td>
			<td style="text-align: center;"><?php echo number_format($summ_deal_preparation_yamarket_fbs, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_real_deal_preparation_yamarket_fbs, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_compens_deal_preparation_yamarket_fbs, 0, ',', ' ');?></td>
		</tr>
		<tr>
			<td style="text-align: right;">Удовлетворено</td>
			<td style="text-align: center;"><?php echo count($deal_won_yamarket_fbs);?></td>
			<td style="text-align: center;"><?php echo number_format($summ_deal_won_yamarket_fbs, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_real_deal_won_yamarket_fbs, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_compens_deal_won_yamarket_fbs, 0, ',', ' ');?></td>
		</tr>
		<tr>
			<td style="text-align: right;">Част. удовлетворено</td>
			<td style="text-align: center;"><?php echo count($deal_won_partly_yamarket_fbs);?></td>
			<td style="text-align: center;"><?php echo number_format($summ_deal_won_partly_yamarket_fbs, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_real_deal_won_partly_yamarket_fbs, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_compens_deal_won_partly_yamarket_fbs, 0, ',', ' ');?></td>
		</tr>
		<tr>
			<td style="text-align: right;">Отклонено</td>
			<td style="text-align: center;"><?php echo count($deal_lose_yamarket_fbs);?></td>
			<td style="text-align: center;"><?php echo number_format($summ_deal_lose_yamarket_fbs, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_real_deal_lose_yamarket_fbs, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_compens_deal_lose_yamarket_fbs, 0, ',', ' ');?></td>
		</tr>
		<tr>
			<th>Название</th>
			<th>Стадия</th>
			<th></th>
			<th></th>
			<th></th>
		</tr>
<?php if (!$link_deal_all_yamarket_fbs['TOTAL'] = "0"):?>
<?php endif;?>
<?php for ($i=0; $i+1 < count($link_deal_all_yamarket_fbs); $i++):?>
	<tr>
			<td style="font-size: 0.5rem; text-align: left;"><?php echo $link_deal_all_yamarket_fbs[$i]['LINK'];?></td>
			<td style="font-size: x-small; background: <?php echo $link_deal_all_yamarket_fbs[$i]['CELL_COLOR'];?>"><?php echo $link_deal_all_yamarket_fbs[$i]['STAGE'];?></td>


<?php if (strpos($link_deal_all_yamarket_fbs[$i]['LINK'], 'ЯМ') || strpos($link_deal_all_yamarket_fbs[$i]['LINK'], 'OZON') !== false):?>
<td><a target="_blank" href="https://b24.topcomputer.ru/local/report/legal_depart/legal-department-claims-MP-more.php?id=<?php echo $deal_all_yamarket_fbs[$i]['ID']?>"><?php echo number_format($link_deal_all_yamarket_fbs[$i]['SUMM'], 0, ',', ' ');?></a></td>
<td><a target="_blank" href="https://b24.topcomputer.ru/local/report/legal_depart/legal-department-claims-MP-more.php?id=<?php echo $deal_all_yamarket_fbs[$i]['ID']?>"><?php echo number_format($link_deal_all_yamarket_fbs[$i]['REAL_SUMM'], 0, ',', ' ');?></a></td>
<td><a target="_blank" href="https://b24.topcomputer.ru/local/report/legal_depart/legal-department-claims-MP-more.php?id=<?php echo $deal_all_yamarket_fbs[$i]['ID']?>"><?php echo number_format($link_deal_all_yamarket_fbs[$i]['COMPENS_SUMM'], 0, ',', ' ');?></a></td>
<?php else: ?>
<td><?php echo number_format($link_deal_all_yamarket_fbs[$i]['SUMM'], 0, ',', ' ');?></td>
<td>
<?php if ($link_deal_all_yamarket_fbs[$i]['REAL_SUMM']>0) { echo number_format($link_deal_all_yamarket_fbs[$i]['REAL_SUMM'], 0, ',', ' ');?>
<?php } else {echo "0";}?>
</td>
<td>
<?php if ($link_deal_all_yamarket_fbs[$i]['COMPENS_SUMM']>0) { echo number_format($link_deal_all_yamarket_fbs[$i]['COMPENS_SUMM'], 0, ',', ' ');?>
<?php } else {echo "0";}?>
</td>
<?php endif;?>

	<?php endfor; ?>
		</tr>
</table>



		<table class="table"> 

		<tr>
			<th><b>Я.Маркет FBY</b></th>
			<th>Кол-во</th>
			<th>Заявл сумма</th>
			<th>Реал сумма</th>
			<th>Компенс</th>
		</tr>
		<tr>
			<td style="text-align: right;">Претензий всего</td>
			<td style="text-align: center;"><?php echo count($deal_all_yamarket_fby);?></td>
			<td style="text-align: center; white-space: nowrap;"><?php echo number_format($summ_deal_all_yamarket_fby, 0, ',', ' ');?></td>
			<td style="text-align: center; white-space: nowrap;"><?php echo number_format($summ_real_deal_all_yamarket_fby, 0, ',', ' ');?></td>
			<td style="text-align: center; white-space: nowrap;"><?php echo number_format($summ_compens_deal_all_yamarket_fby, 0, ',', ' ');?></td>
		</tr>
	<tr><th colspan="5">Lead Time: <?php echo number_format($summ_time_deal_all_yamarket_fby_average, 0, ',', ' ')." мин";?></th></tr>
		<tr>
			<td style="text-align: right;">Новых претензий</td>
			<td style="text-align: center;"><?php echo count($deal_new_yamarket_fby);?></td>
			<td style="text-align: center;"><?php echo number_format($summ_deal_new_yamarket_fby, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_real_deal_new_yamarket_fby, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_compens_deal_new_yamarket_fby, 0, ',', ' ');?></td>
		</tr>
		<tr>
			<td style="text-align: right;">Подача претензии</td>
			<td style="text-align: center;"><?php echo count($deal_get_yamarket_fby);?></td>
			<td style="text-align: center;"><?php echo number_format($summ_deal_get_yamarket_fby, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_real_deal_get_yamarket_fby, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_compens_deal_get_yamarket_fby, 0, ',', ' ');?></td>
		</tr>
		<tr>
			<td style="text-align: right;">Ответ по претензии</td>
			<td style="text-align: center;"><?php echo count($deal_answer_yamarket_fby);?></td>
			<td style="text-align: center;"><?php echo number_format($summ_deal_answer_yamarket_fby, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_real_deal_answer_yamarket_fby, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_compens_deal_answer_yamarket_fby, 0, ',', ' ');?></td>
		</tr>
		<tr>
			<td style="text-align: right;">Объед. товаров</td>
			<td style="text-align: center;"><?php echo count($deal_preparation_yamarket_fby);?></td>
			<td style="text-align: center;"><?php echo number_format($summ_deal_preparation_yamarket_fby, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_real_deal_preparation_yamarket_fby, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_compens_deal_preparation_yamarket_fby, 0, ',', ' ');?></td>
		</tr>
		<tr>
			<td style="text-align: right;">Удовлетворено</td>
			<td style="text-align: center;"><?php echo count($deal_won_yamarket_fby);?></td>
			<td style="text-align: center;"><?php echo number_format($summ_deal_won_yamarket_fby, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_real_deal_won_yamarket_fby, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_compens_deal_won_yamarket_fby, 0, ',', ' ');?></td>
		</tr>
		<tr>
			<td style="text-align: right;">Част. удовлетворено</td>
			<td style="text-align: center;"><?php echo count($deal_won_partly_yamarket_fby);?></td>
			<td style="text-align: center;"><?php echo number_format($summ_deal_won_partly_yamarket_fby, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_real_deal_won_partly_yamarket_fby, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_compens_deal_won_partly_yamarket_fby, 0, ',', ' ');?></td>
		</tr>
		<tr>
			<td style="text-align: right;">Отклонено</td>
			<td style="text-align: center;"><?php echo count($deal_lose_yamarket_fby);?></td>
			<td style="text-align: center;"><?php echo number_format($summ_deal_lose_yamarket_fby, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_real_deal_lose_yamarket_fby, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_compens_deal_lose_yamarket_fby, 0, ',', ' ');?></td>
		</tr>
		<tr>
			<th>Название</th>
			<th>Стадия</th>
			<th></th>
			<th></th>
			<th></th>
		</tr>
<?php if (!$link_deal_all_yamarket_fby['TOTAL'] = "0"):?>
<?php endif;?>
<?php for ($i=0; $i+1 < count($link_deal_all_yamarket_fby); $i++):?>
	<tr>
			<td style="font-size: 0.5rem; text-align: left;"><?php echo $link_deal_all_yamarket_fby[$i]['LINK'];?></td>
			<td style="font-size: x-small; background: <?php echo $link_deal_all_yamarket_fby[$i]['CELL_COLOR'];?>"><?php echo $link_deal_all_yamarket_fby[$i]['STAGE'];?></td>

<?php if (strpos($link_deal_all_yamarket_fby[$i]['LINK'], 'ЯМ') || strpos($link_deal_all_yamarket_fby[$i]['LINK'], 'OZON') !== false):?>
<td><a target="_blank" href="https://b24.topcomputer.ru/local/report/legal_depart/legal-department-claims-MP-more.php?id=<?php echo $deal_all_yamarket_fby[$i]['ID']?>"><?php echo number_format($link_deal_all_yamarket_fby[$i]['SUMM'], 0, ',', ' ');?></a></td>
<td><a target="_blank" href="https://b24.topcomputer.ru/local/report/legal_depart/legal-department-claims-MP-more.php?id=<?php echo $deal_all_yamarket_fby[$i]['ID']?>"><?php echo number_format($link_deal_all_yamarket_fby[$i]['REAL_SUMM'], 0, ',', ' ');?></a></td>
<td><a target="_blank" href="https://b24.topcomputer.ru/local/report/legal_depart/legal-department-claims-MP-more.php?id=<?php echo $deal_all_yamarket_fby[$i]['ID']?>"><?php echo number_format($link_deal_all_yamarket_fby[$i]['COMPENS_SUMM'], 0, ',', ' ');?></a></td>
<?php else: ?>
<td><?php echo number_format($link_deal_all_yamarket_fby[$i]['SUMM'], 0, ',', ' ');?></td>
<td>
<?php if ($link_deal_all_yamarket_fby[$i]['REAL_SUMM']>0) { echo number_format($link_deal_all_yamarket_fby[$i]['REAL_SUMM'], 0, ',', ' ');?>
<?php } else {echo "0";}?>
</td>
<td>
<?php if ($link_deal_all_yamarket_fby[$i]['COMPENS_SUMM']>0) { echo number_format($link_deal_all_yamarket_fby[$i]['COMPENS_SUMM'], 0, ',', ' ');?>
<?php } else {echo "0";}?>
</td>
<?php endif;?>

	<?php endfor; ?>
		</tr>
</table>


		<table class="table"> 

		<tr>
			<th><b>Я.Маркет FBO</b></th>
			<th>Кол-во</th>
			<th>Заявл сумма</th>
			<th>Реал сумма</th>
			<th>Компенс</th>
		</tr>
		<tr>
			<td style="text-align: right;">Претензий всего</td>
			<td style="text-align: center;"><?php echo count($deal_all_yamarket_fbo);?></td>
			<td style="text-align: center; white-space: nowrap;"><?php echo number_format($summ_deal_all_yamarket_fbo, 0, ',', ' ');?></td>
			<td style="text-align: center; white-space: nowrap;"><?php echo number_format($summ_real_deal_all_yamarket_fbo, 0, ',', ' ');?></td>
			<td style="text-align: center; white-space: nowrap;"><?php echo number_format($summ_compens_deal_all_yamarket_fbo, 0, ',', ' ');?></td>
		</tr>
	<tr><th colspan="5">Lead Time: <?php echo number_format($summ_time_deal_all_yamarket_fbo_average, 0, ',', ' ')." мин";?></th></tr>
		<tr>
			<td style="text-align: right;">Новых претензий</td>
			<td style="text-align: center;"><?php echo count($deal_new_yamarket_fbo);?></td>
			<td style="text-align: center;"><?php echo number_format($summ_deal_new_yamarket_fbo, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_real_deal_new_yamarket_fbo, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_compens_deal_new_yamarket_fbo, 0, ',', ' ');?></td>
		</tr>
		<tr>
			<td style="text-align: right;">Подача претензии</td>
			<td style="text-align: center;"><?php echo count($deal_get_yamarket_fbo);?></td>
			<td style="text-align: center;"><?php echo number_format($summ_deal_get_yamarket_fbo, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_real_deal_get_yamarket_fbo, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_compens_deal_get_yamarket_fbo, 0, ',', ' ');?></td>
		</tr>
		<tr>
			<td style="text-align: right;">Ответ по претензии</td>
			<td style="text-align: center;"><?php echo count($deal_answer_yamarket_fbo);?></td>
			<td style="text-align: center;"><?php echo number_format($summ_deal_answer_yamarket_fbo, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_real_deal_answer_yamarket_fbo, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_compens_deal_answer_yamarket_fbo, 0, ',', ' ');?></td>
		</tr>
		<tr>
			<td style="text-align: right;">Объед. товаров</td>
			<td style="text-align: center;"><?php echo count($deal_preparation_yamarket_fbo);?></td>
			<td style="text-align: center;"><?php echo number_format($summ_deal_preparation_yamarket_fbo, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_real_deal_preparation_yamarket_fbo, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_compens_deal_preparation_yamarket_fbo, 0, ',', ' ');?></td>
		</tr>
		<tr>
			<td style="text-align: right;">Удовлетворено</td>
			<td style="text-align: center;"><?php echo count($deal_won_yamarket_fbo);?></td>
			<td style="text-align: center;"><?php echo number_format($summ_deal_won_yamarket_fbo, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_real_deal_won_yamarket_fbo, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_compens_deal_won_yamarket_fbo, 0, ',', ' ');?></td>
		</tr>
		<tr>
			<td style="text-align: right;">Част. удовлетворено</td>
			<td style="text-align: center;"><?php echo count($deal_won_partly_yamarket_fbo);?></td>
			<td style="text-align: center;"><?php echo number_format($summ_deal_won_partly_yamarket_fbo, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_real_deal_won_partly_yamarket_fbo, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_compens_deal_won_partly_yamarket_fbo, 0, ',', ' ');?></td>
		</tr>
		<tr>
			<td style="text-align: right;">Отклонено</td>
			<td style="text-align: center;"><?php echo count($deal_lose_yamarket_fbo);?></td>
			<td style="text-align: center;"><?php echo number_format($summ_deal_lose_yamarket_fbo, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_real_deal_lose_yamarket_fbo, 0, ',', ' ');?></td>
			<td style="text-align: center;"><?php echo number_format($summ_compens_deal_lose_yamarket_fbo, 0, ',', ' ');?></td>
		</tr>
		<tr>
			<th>Название</th>
			<th>Стадия</th>
			<th></th>
			<th></th>
			<th></th>
		</tr>
<?php if (!$link_deal_all_yamarket_fbo['TOTAL'] = "0"):?>
<?php endif;?>
<?php for ($i=0; $i+1 < count($link_deal_all_yamarket_fbo); $i++):?>
	<tr>
			<td style="font-size: 0.5rem; text-align: left;"><?php echo $link_deal_all_yamarket_fbo[$i]['LINK'];?></td>
			<td style="font-size: x-small; background: <?php echo $link_deal_all_yamarket_fbo[$i]['CELL_COLOR'];?>"><?php echo $link_deal_all_yamarket_fbo[$i]['STAGE'];?></td>

<?php if (strpos($link_deal_all_yamarket_fbo[$i]['LINK'], 'ЯМ') || strpos($link_deal_all_yamarket_fbo[$i]['LINK'], 'OZON') !== false):?>
<td><a target="_blank" href="https://b24.topcomputer.ru/local/report/legal_depart/legal-department-claims-MP-more.php?id=<?php echo $deal_all_yamarket_fbo[$i]['ID']?>"><?php echo number_format($link_deal_all_yamarket_fbo[$i]['SUMM'], 0, ',', ' ');?></a></td>
<td><a target="_blank" href="https://b24.topcomputer.ru/local/report/legal_depart/legal-department-claims-MP-more.php?id=<?php echo $deal_all_yamarket_fbo[$i]['ID']?>"><?php echo number_format($link_deal_all_yamarket_fbo[$i]['REAL_SUMM'], 0, ',', ' ');?></a></td>
<?php else: ?>
<td><?php echo number_format($link_deal_all_yamarket_fbo[$i]['SUMM'], 0, ',', ' ');?></td>
<td>
<?php if ($link_deal_all_yamarket_fbo[$i]['REAL_SUMM']>0) { echo number_format($link_deal_all_yamarket_fbo[$i]['REAL_SUMM'], 0, ',', ' ');?>
<?php } else {echo "0";}?>
</td>
<td>
<?php if ($link_deal_all_yamarket_fbo[$i]['COMPENS_SUMM']>0) { echo number_format($link_deal_all_yamarket_fbo[$i]['COMPENS_SUMM'], 0, ',', ' ');?>
<?php } else {echo "0";}?>
</td>
<?php endif;?>


	<?php endfor; ?>

		</tr>
</table>
</div>