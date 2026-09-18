<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();

$rptList = [];

$rptM = [];
$rptDSets = [];
for($mth = 1; $mth <=12; $mth++)
{
	if($mth <= date('m') )
	{
		$dateObj   = DateTime::createFromFormat('!m', $mth);
		$monthName = $dateObj->format('M'); 
		$rptM[$mth]=['name' => $monthName];

		$rptDSets[] = [
		    'label' => $monthName,
		    'data'  => [],
          
	  	];

	}
}

$thclass = 'text-center font-weight-bold';
$mnthTH = implode('</th><th class="'.$thclass.' bg-light">', array_map(function ($v, $k) { return $v['name']; }, $rptM, array_keys($rptM)));

$hfcode = @$CI->userregistrationinfo['HealthFacilityCode'];
$hfyear = date('Y');

$getConditionList = $CI->sqlhelper->local->select("ref_primarycondition")->ex_select()->result();
$zsRow = '';
$zrptLbl = [];
$zData = [];
foreach($getConditionList['Data'] as $cl_r => $cl_c)
{
	$zrptLbl[] = $cl_c['display'];
	$rtNo = 0;

	$tmp = '
		<tr>
			<td class="font-weight-bold font-size-12">'.$cl_c['display'].'</td>';

	// ZPAMS-FIX (2026-09): this loop previously filled $rNo with random_int(1,500) placeholder
	// data instead of a real query -- every Health Facility user saw fabricated counts on their
	// dashboard. Replaced with the facility-scoped query below.
	foreach($rptM as $mk => $mc)
	{
		$rq = $CI->sqlhelper->local->sql("
			SELECT COUNT(*) as cnt
			FROM trans_preauth_form d
			JOIN ref_primarycondition_preauthlist c ON d.preauth_type = c.code
			WHERE c.primarycondition_code = '".$cl_c['code']."'
			AND d.preauth_status = 6
			AND d.healthfacility_code = '".$hfcode."'
			AND YEAR(d.submitted_datetime) = '".$hfyear."'
			AND MONTH(d.submitted_datetime) = '".$mk."'
		")->result();
		$rNo = (int) @$rq['Data'][0]['cnt'];
		$rtNo += $rNo;
		$tmp .= '<td class="'.$thclass.' border-right w-40  font-size-12"><span id="rpt_001_'.$cl_c['code'].'_'.$mk.'">'.$rNo.'</span></td>
		';

		$rptDSets[ $mk-1 ]['data'][] = $rNo;
	}

	$tmp .= '<td class="'.$thclass.' border-right w-40  font-size-12"><span id="rpt_001_'.$cl_c['code'].'_total">'.$rtNo.'</span></td>
		</tr>
	';

	$zsRow .= str_replace('_code_','_'.$cl_c['code'].'_',$tmp);
}



?>

<div class="col-lg-12 col-md-12">
	<div id="rpt_001_01" class="panel">
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo date('Y'); ?> Monthly Pre-Authorization Request ( Approved ) </h3>
			<div class="panel-actions">
				<a targetid="rpt_001_01" name="rptfullscreenlink" class="panel-action icon md-fullscreen" aria-hidden="true"></a>
			</div>
		</div>
		<div class="panel-body">
			<div class="row">
				<div id="rpt_001_01_table" class="col-lg-6 col-md-6">
					<table class="table table-sm table-bordered mb-0">
						<thead class="">
							<tr class="">
								<th class="font-weight-bold bg-light">Z-Benefits Services</th>
								<?php echo '<th class="'.$thclass.' bg-light">'.@$mnthTH.'</th>'; ?>
								<th class="<?php echo $thclass; ?> bg-light ">Total</th>
							</tr>
						</thead>
						<tbody>
							<?php echo $zsRow; ?>
						</tbody>
						<tfoot>
							<tr>
								<td class="font-weight-bold text-right bg-green-50">TOTAL</td>
								<td class="<?php echo $thclass; ?> bg-green-50"><?php echo implode('</td><td class="'.$thclass.'  bg-green-50">', array_map(function ($v, $k) { return '<span id="rpt_001_total_'.$k.'"></span>'; }, $rptM, array_keys($rptM))); ?></td>
								<td class="font-weight-bold bg-green-50"><span id="rpt_001_total_total"></span></td>
							</tr>
						</tfoot>
					</table>
				</div>
				<div class="col-lg-6 col-md-6">
					<div style=""><canvas id="rpt_001_chartcontainer"></canvas></div>
				</div>
			</div>
		</div>
	</div>
</div>

<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">
$(document).ready(function(){

	const rptLbl = <?php echo json_encode($zrptLbl); ?>;
	const rptDSets = <?php echo json_encode($rptDSets); ?>;
	const rpt01_topLabels = {
		id: 'topLabels',
		afterDatasetsDraw(chart,args,pluginOptions){
			const { ctx, scales: { x,y } } = chart;

			chart.data.datasets[0].data.forEach((datapoint,index) => {
				const datasetArray = [];
				chart.data.datasets.forEach((dataset) => {
					datasetArray.push(dataset.data[index])
				})

				function totalSum(total,values){
					return total + values;
				};

				let sumT = datasetArray.reduce(totalSum,0);

				ctx.font = 'bold 10px sans-serif';
				ctx.textAlign = 'center';
				ctx.fillText(sumT, x.getPixelForValue(index), chart.getDatasetMeta( (datasetArray.length - 1) ).data[index].y - 5 );
			})
		}
	};

	const rpt_001_footer = (tooltipItems) => {
		let sum = 0;
		tooltipItems.forEach(function(tooltipItem) {
			sum += tooltipItem.parsed.y;
		});
		return 'Total : ' + sum;
	};

	const config ={
	      type: 'bar',
	      data: {
	        labels: rptLbl,
	        datasets:rptDSets,
	      },
	      options: {
	      	interaction: {
				intersect: false,
				mode: 'index',
		    },
		    plugins: {
				title: {
					display: true,
					text: 'Z-BENEFITS PRE-AUTHORIZATION'
				},
		      	legend: {
	                display: true,
	           		position: 'top'
	            },
	            datalabels: {
	            	anchor: 'center',
	            	align: 'center',
					color: 'black',
					font: {
					  weight: 'normal',
					  size: '8'
					},
					formatter:  (value, ctx) => {
						return value;
			        },
				 	display: function(context) {
				 		return context.chart.isDatasetVisible(context.datasetIndex);
			        },
			        
				},
				tooltip: {
					enabled: true,
			  		callbacks: {
				    	footer: rpt_001_footer,
			  		}
				}
		    },
		    aspectRatio: 5 / 3,
		    responsive: true,
		    scales: {
		      x: {
		        stacked: true,
		      },
		      y: {
		        stacked: true,
		        beginAtZero:true
		      }
		    },
		    
		  },
		  plugins:[ChartDataLabels, rpt01_topLabels]
	    };

	const rpt_001 = new Chart(document.getElementById('rpt_001_chartcontainer'),config);

});
</script>

