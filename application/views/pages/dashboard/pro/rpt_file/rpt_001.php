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

	foreach($rptM as $mk => $mc)
	{
		$rNo = 0; //random_int(1, 500);
		$rtNo += $rNo;
		$tmp .= '<td class="'.$thclass.' border-right w-40  font-size-12"><span id="rpt_001_'.$cl_c['code'].'_'.str_pad($mk,2,0,STR_PAD_LEFT).'">'.$rNo.'</span></td>
		';

		$rptDSets[ $mk-1 ]['data'][] = $rNo;
	}

	$tmp .= '<td class="'.$thclass.' border-right w-40  font-size-12"><span id="rpt_001_'.$cl_c['code'].'_Total">'.$rtNo.'</span></td>
		</tr>
	';

	$zsRow .= str_replace('_code_','_'.$cl_c['code'].'_',$tmp);
}



?>

<div class="col-lg-12 col-md-12">
	<div id="rpt_001_01" class="panel" data-load-callback="rpt_001_01_RefreshCallback">
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo date('Y'); ?> Monthly Pre-Authorization Request ( Approved ) </h3>
			<div class="panel-actions">
				<a id="rpt_001_01_refresh" class="panel-action icon md-refresh-alt" data-toggle="panel-refresh" data-load-type="round-circle" aria-hidden="true"></a>
				<a targetid="rpt_001_01" name="rptfullscreenlink" class="panel-action icon md-fullscreen" aria-hidden="true"></a>
			</div>
		</div>
		<div class="panel-body">
			<div class="row">
				<div id="rpt_001_01_table" class="col-lg-6 col-md-6" data-mh="rpt_001_01">
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
								<td class="<?php echo $thclass; ?> bg-green-50"><?php echo implode('</td><td class="'.$thclass.'  bg-green-50">', array_map(function ($v, $k) { return '<span id="rpt_001_gTotal_'.str_pad($k,2,0,STR_PAD_LEFT).'">0</span>'; }, $rptM, array_keys($rptM))); ?></td>
								<td class="font-weight-bold bg-green-50"><span id="rpt_001_gTotal_Total">0</span></td>
							</tr>
						</tfoot>
					</table>
				</div>
				<div class="col-lg-6 col-md-6">
					<div style="" data-mh="rpt_001_01"><canvas id="rpt_001_chartcontainer"></canvas></div>
				</div>
			</div>
		</div>
	</div>
</div>

<script id="removethis" nonce="<?php echo $CI->nonceV; ?>" type="text/javascript">
$(document).ready(function(){
	window.rpt_001_01_RefreshCallback = function(){
		var self = this;
		rptData = new FormData();
		var apiPath = site_url+'preauthorization/dashboard/rpt_monthlyrequest';
		var apiResult = function(pp,rp){
			if(rp.Status == 1)
			{
				rpt_001_statstbl(rp.Message);
				self.done();
			}
		}
		submitFormData('json','',apiPath,rptData,apiResult,false,true,0);
	};

	let rdsets_m = {};
	let rdset = [];
	const rpt_001_statstbl = (rptData) => {	
		rdset = [];
		for( var ri in rptData )
		{
			var srow = rptData[ri];
			for( var fld in srow)
			{
				var ifld = ['Code','Display'];
				if( !ifld.includes(fld) )
				{
					$("span[id='rpt_001_"+srow['Code']+"_"+fld+"']").html(numberWithCommas(srow[fld]));
					var gTotal = parseInt($("span[id='rpt_001_gTotal_"+fld+"']").text()) + parseInt(srow[fld]);
					$("span[id='rpt_001_gTotal_"+fld+"']").html(numberWithCommas(gTotal));

					if(fld !== 'Total')
					{
						if( !rdsets_m.hasOwnProperty(fld) ){
							
							const rdate = new Date('2025-'+fld+'-01');
							const monthNameShort = rdate.toLocaleString('en-US', { month: 'short' });

							rdsets_m[fld] = {
								'label' : monthNameShort,
								'data'  : [],
							};
						}

						
						
					}
				}
			}
		}

		for(var rr in rdsets_m)
		{
			rdset.push(rdsets_m[rr]);
		}

		rpt_001.data.datasets = rdset;
		rpt_001.update();

	};

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

	setInterval(function(){ $("a[id='rpt_001_01_refresh']").click(); }, 30000);
});
</script>

