<?php
/**
*	GNU AFFERO GENERAL PUBLIC LICENSE 
*	   Version 3, 19 November 2007
* This software is licensed under the GNU AGPL Version 3
* 	(see the file LICENSE for details)
*/
?>
<?php  
echo eval($node->widgetConfig->header);  
$table_rows = array();
$years = array();
foreach( $node->data as $row){	
	$length =  $row['indentation_level'];
	$spaceString = '&nbsp';
	while($length > 0){
		$spaceString .= '&nbsp';
		$length -=1;
	}
	$table_rows[$row['display_order']]['category'] =  $row['category'];
	$table_rows[$row['display_order']]['highlight_yn'] = $row['highlight_yn'];
	$table_rows[$row['display_order']]['indentation_level'] = $row['indentation_level'];
	$table_rows[$row['display_order']]['amount_display_type'] = $row['amount_display_type'];
	$table_rows[$row['display_order']][$row['fiscal_year']]['amount'] = $row['amount'];
	$years[$row['fiscal_year']] = 	$row['fiscal_year'];
}
rsort($years);
if(preg_match('/featuredtrends/',$_GET['q'])){
  $links = array(l(t('Home'), ''), l(t('Trends'), 'featured-trends'),
                '<a href="/featured-trends?slide=2">Capital Projects Fund Aid Revenues</a>',
               'Capital Projects Fund Aid Revenues by Agency Details');
  drupal_set_breadcrumb($links);
}
?>

<?php if($node->widgetConfig->table_title){echo '<h3>'.$node->widgetConfig->table_title.'</h3>';} ?>

<a class="trends-export" href="/export/download/trends_capital_proj_rev_by_agency_csv?dataUrl=/node/<?php echo $node->nid ?>">Export</a>
<table class="fy-ait">
  <tbody>
  <tr>
    <td style="width:350px;padding:0;">&nbsp;</td>
    <td class="bb">Fiscal Year<br>(Amounts in Thousands)</td>
  </tr>
  </tbody>
</table>
<table id="table_<?php echo widget_unique_identifier($node) ?>" style='display:none' class="trendsShowOnLoad <?php echo $node->widgetConfig->html_class ?>">
    <?php
    if (isset($node->widgetConfig->caption_column)) {
        echo '<caption>' . $node->data[0][$node->widgetConfig->caption_column] . '</caption>';
    }
    else if (isset($node->widgetConfig->caption)) {
        echo '<caption>' . $node->widgetConfig->caption . '</caption>';
    }
    ?>
    <thead>
    <tr class="second-row">
        <th><div><br/></div></th>
        <?php
        foreach ($years as $year)
           echo "<th>&nbsp;</th><th class='number'>" . $year . "</th>";
        ?>
        <th>&nbsp;</th>
    </tr>
    </thead>

    <tbody>

    <?php
        $count = 0;
        foreach($table_rows as $row){
            $cat_class = "";
            $dollar_sign = "";
            $count++;
            if($count == 2 || $count == count($table_rows)){
                $dollar_sign = "<div class='dollarItem' >$</div>";
            }
            
            if($row['highlight_yn'] == 'Y')
                $cat_class = "highlight ";
            $cat_class .= "level" . $row['indentation_level'];
            $amount_class = "number";

            if( $row['amount_display_type'])
                $amount_class .= " amount-" . $row['amount_display_type'];

            $row['category'] = (isset($row['category'])?$row['category']:'&nbsp;');
            
            
            $conditionCategory = ($row['category']?$row['category']:'&nbsp;');
            switch($conditionCategory){
            	/*
            	case "Department of Small Business Services":
            		$conditionCategory = "<div class='" . $cat_class . "'>Department of Small<br><span style='padding-left:10px;'>Business Services<span></div>";
            		break;
            	case "Department of Citywide Administrative Services":
            		$conditionCategory = "<div class='" . $cat_class ."'>Department of Citywide<br><span style='padding-left:10px;'>Administrative Services</span></div>";
            		break;
            	case "Department of Information Technology and Telecommunications":
            		$conditionCategory = "<div class='" .$cat_class . "'>Department of Information<br><span style='padding-left:10px;'>Technology and<span><br><span style='padding-left:10px;'>Telecommunications</span></div>";
            		break;
            	case "Total General Government":
            		$conditionCategory = "<div class='level5'>Total General<br><span style='padding-left:10px;'>Government<span></div>";
            		break;
            	case "Total Public Safety and Judicial":
            		$conditionCategory = "<div class='level5'>Total Public Safety<br><span style='padding-left:10px;'>and Judicial<span></div>";
            		break;
            	case "Department of Environmental Protection":
            		$conditionCategory = "<div class='" . $cat_class . "'>Department of Environmental<br><span style='padding-left:10px;'>Protection<span></div>";
            		break;
            	case "Total Environmental Protection":
            		$conditionCategory = "<div class='level4'>Total Environmental<br><span style='padding-left:10px;'>Protection<span></div>";
            		break;
            	case "Department of Transportation":
            		$conditionCategory = "<div class='" . $cat_class . "'>Department of<br><span style='padding-left:10px;'>Transportation<span></div>";
            		break;
            	case "Total Transportation Services":
            		$conditionCategory = "<div class='level4'>Total Transportation<br><span style='padding-left:10px;'>Services<span></div>";
            		break;
            	case "Parks, Recreation, and Cultural Activities:":
            		$conditionCategory = "<div class='" . $cat_class . "'>Parks, Recreation, and<br>Cultural Activities:</div>";
            		break;
            	case "Department of Parks and Recreation":
            		$conditionCategory = "<div class='" . $cat_class . "'>Department of Parks<br><span style='padding-left:10px;'>and Recreation<span></div>";
            		break;
            	case "Department of Cultural Affairs":
            		$conditionCategory = "<div class='" . $cat_class . "'>Department of Cultural<br><span style='padding-left:10px;'>Affairs<span></div>";
            		break;
            	case "Total Parks, Recreation, and Cultural Activities":
            		$conditionCategory = "<div class='" . $cat_class . "'>Total Parks, Recreation,<br><span style='padding-left:10px;'>and Cultural Activities<span></div>";
            		break;
            	case "Department of Housing Preservation and Development":
            		$conditionCategory = "<div class='" . $cat_class . "'>Department of Housing<br><span style='padding-left:10px;'>Preservation and</span><br><span style='padding-left:10px;'>Development<span></div>";
            		break;
            	*/
            	default:
            		$conditionCategory = "<div class='" . $cat_class . "' >" . $row['category'] . "</div>";
            		break;
            }
            
            

            echo "<tr>
            <td class='text' >" . $conditionCategory . "</td>";

            foreach ($years as $year){
                echo "<td><div>&nbsp;</div></td>";
                
                if($row[$year]['amount'] > 0){
                    echo "<td class='" . $amount_class . " ' >".$dollar_sign. "<div>" . number_format($row[$year]['amount']) . "</div></td>";
                }else if($row[$year]['amount'] < 0){
                   echo "<td class='" . $amount_class . " ' >".$dollar_sign. "<div>(" . number_format(abs($row[$year]['amount'])) . ")</div></td>";
                }else if($row[$year]['amount'] == 0){
                     if(strpos($row['category'], ':'))
                        echo "<td class='" . $amount_class . " ' ><div>" . '&nbsp;' . "</div></td>";
                     else
                        echo "<td class='" . $amount_class . " ' ><div>" . '-' . "</div></td>";
                }
                }
            echo "<td>&nbsp;</td>";
            echo "</tr>";
        }
    ?>

    </tbody>
</table>
<?php
	widget_data_tables_add_js($node);
?>
<div class="footnote">
<p>Source: Comprehensive Annual Financial Reports of the Comptroller.</p>
</div>