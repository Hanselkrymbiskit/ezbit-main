Chart.plugins.register({
  id: 'paddingBelowLegends',
  beforeInit: function(chart, options) {
    chart.legend.afterFit = function() {
      this.height = this.height + 15;
    };
  }
});


var ChartObjects = new Object();
/*
	This Function Helps to Initiate ChartJS - Bar Chart
	ctx
	bartype = 'bar' or 'horizontalBar'
	stackedbar = true or false
	chartTitle = 'Chart Title'
	chartLabels = [] Object Chart Labels
	chartDataSets = [
										{
						            label: 'Total # of Patients',
						            data: [],
						            backgroundColor: [],
						            borderColor: [],
						            borderWidth: 1
						        }
									]

	chartObject = {
		'ctx' : '',
		'bartype' : 'bar',
		'stackedbar' : false,
		'chartTitle' : '',
		'chartLabels' : [],
		'chartDataSets' : [],
	}
*/
// function generateChart_Bar(ctx,bartype,stackedbar,chartTitle,chartLabels,chartDataSets,chartLegendDisplay)
function generateChart_Bar(cobj)
{
	obj = {
		'update':false,
		'ctx' : '',
		'bartype' : 'bar',
		'stackedbar' : false,
		'chartTitle' : '',
		'chartLabels' : [],
		'chartDataSets' : [],
		'legendDisplay' : false,
	}

	for(var x in obj)
	{
		if( cobj.hasOwnProperty(x) )
		{
			obj[x] = cobj[x];
		}
	}

	var chartDataConfig = {
	        labels: obj['chartLabels'],
	        datasets: obj['chartDataSets'],
	    };
  
	var chartOptions = {
			title: {
				display: (obj['chartTitle'] == "") ? false : true,
				fontSize:16,
				text: obj['chartTitle']
			},
			legend: {
		          display: obj['legendDisplay'],
		          paddingBottom:40,
		     },
			tooltips: {
				mode: 'index',
				intersect: false
			},
			responsive: true,
			maintainAspectRatio: false,
	        scales: {

	            xAxes: [{   
            		offset:true, 

            		ticks: {
	                    beginAtZero: true,
	                },     
	                stacked: obj['stackedbar']
	            }],
	            yAxes: [{

	            		ticks: {
		                    beginAtZero: true,
		                },
	                stacked: obj['stackedbar'],
	                beforeFit: function (scale) {

				            // // See what you can set in scale parameter
				            // console.log(scale);

				            // // Find max value in your dataset
				            // let maxValue = 0;
				            // if (scale.chart.config && scale.chart.config.data && scale.chart.config.data.datasets) {
				            //   scale.chart.config.data.datasets.forEach(dataset => {
				            //     if (dataset && dataset.data) {
				            //       dataset.data.forEach(value => {
				            //         if (value > maxValue) {
				            //           maxValue = value
				            //         }
				            //       })
				            //     }
				            //   })
				            // }

				            // // // // After, set max option !!!
				            // console.log( scale.ticksAsNumbers[0] );
				            // console.log( maxValue );
				            // // scalemax =scale.max + 10;
				            // if( parseInt(scale.ticksAsNumbers[0]) <= parseInt(maxValue) )
				            // {
				            	// console.log( scale.ticksAsNumbers[0] + scale.ticksAsNumbers[(scale.ticksAsNumbers.length) - 2] );
				            	// scale.options.ticks.max = parseInt(scale.ticksAsNumbers[0]) + 5;
				            // }
				            
				            // console.log(maxValue);
				          }
	            }]
	        },
	        plugins:{
        		paddingBelowLegends:true,
        		datalabels: {

	                formatter: function(value, context) {
					    return (value > 0) ? value : "";
					}
	            }
		    },
	        animation: {
	            onProgress: function(animation) {
	                var value = animation.animationObject.currentStep / animation.animationObject.numSteps;
	            },
	            onComplete: function(){
	            	  
	            	var chartInstance = this.chart;
	            	
	                var ctx = chartInstance.ctx;
	                if(ChartObjects[ctx.canvas.id]['stackedbar'] == true)
	                {
	                	ctx.font = Chart.helpers.fontString(10, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
		           		ctx.textAlign = 'center';

			           		let dIndex = this.data.datasets.length;
			           		this.data.datasets[0].data.forEach(function(data, index) {
									   	var total = data;
		           				for (i = 1; i < dIndex; i++)
		           				{
									total = parseInt(total) + parseInt(this.data.datasets[i].data[index]);
								}

								var meta = chartInstance.controller.getDatasetMeta(dIndex - 1);
								var posX = meta.data[index]._model.x;
								var posY = meta.data[index]._model.y;
								ctx.fillStyle = 'black';
								if(total > 0)
								{
									ctx.fillText(total, posX, posY - 10);
								}
								
							}, this);       	
	                }
	                else
	                {
	                	ctx.font = Chart.helpers.fontString(10, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
		           		ctx.textAlign = 'right';

		           		let dIndex = this.data.datasets.length;
			           		this.data.datasets[0].data.forEach(function(data, index) {
								var total = data;
								var iSeries = 1;	
								var posXGreater = 0;								
		           				for (i = 1; i < dIndex; i++)
		           				{
									
									total = parseInt(total) + parseInt(this.data.datasets[i].data[index]);
									iSeries = i;
								}

								for(ix = 1; ix <= dIndex; ix++)
								{
									var xmeta = chartInstance.controller.getDatasetMeta(ix - 1);
									
									if( parseInt(posXGreater) <  parseInt(xmeta.data[index]._model.x) )
									{
										posXGreater = parseInt(xmeta.data[index]._model.x);
									}
								}

								var meta = chartInstance.controller.getDatasetMeta(dIndex - 1);
								var posX = meta.data[index]._model.x;
								var posY = meta.data[index]._model.y;

								ctx.fillStyle = 'black';
								if(total > 0)
								{
									ctx.fillText(total, posXGreater + 20, posY - ((dIndex > 1) ? (dIndex * 5) : 0 ) );
								}
								
							}, this);     
	                }
	            }
	        },
	       
	    };
	 
	if(!obj['update'])
	{
		ChartObjects[obj['ctx'].attr('id')] = new Chart(obj['ctx'], {
		    type: obj['bartype'],
		    data: chartDataConfig,
		    options: chartOptions
		});

		ChartObjects[obj['ctx'].attr('id')]['stackedbar']= obj['stackedbar'];
	}
	else
	{
		ChartObjects[obj['ctx'].attr('id')].config.data = chartDataConfig;
		ChartObjects[obj['ctx'].attr('id')].options.title.text = obj['chartTitle'];
		// ChartObjects[obj['ctx'].attr('id')].config.options = chartOptions;
		window.ChartObjects[obj['ctx'].attr('id')].update();
	}

}

/*
	This Function Helps to Initiate ChartJS - Bar Chart
	ctx
	pietype = 'pie' or 'doughnut'
	stackedbar = true or false
	chartTitle = 'Chart Title'
	chartLabels = [] Object Chart Labels
	chartDataSets = [
										{
						            label: '',
						            data: [],
						            backgroundColor: [],
						            borderColor: [],
						            borderWidth: 1
						        }
									]
*/
function generateChart_PieDougnut(ctx,pietype,chartTitle,chartLabels,chartDataSets)
{

	pietype = typeof pietype !== 'undefined' ? pietype : 'pie';
	chartTitle = typeof chartTitle !== 'undefined' ? chartTitle : '';


	var chartDataConfig = {
	        labels: chartLabels,
	        datasets: chartDataSets,
	    };

	var chartOptions = {
					title: {
						display: (chartTitle == "") ? false : true,
						fontSize:16,
						text: chartTitle
					},
					legend: {
		          display: true,
		      },
					tooltips: {
						mode: 'index',
						intersect: false
					},
					responsive: true,
					maintainAspectRatio: false,
					plugins: {
				    
				      paddingBelowLegends:true,
				      datalabels: {
	                formatter: function(value, context) {
	                	let sum = 0
	                	for(var x in context.dataset._meta)
	                	{
	                		sum = context.dataset._meta[x].total;
                		}

                		let percentage = "";
                		if( sum !== 0)
                		{
                			percentage = (value * 100 / sum);
                			percentage = (( isInteger(percentage) ) ? percentage : percentage.toFixed(2) )  + "%";
                		}
                		else
                		{
                			percentage = "";
                		}
						        
						        return percentage;
									}
	            }
			    },
	        animation: {
						animateScale: true,
						animateRotate: true,
						duration: 500,
    				easing: "easeOutQuart",
						onProgress: function(animation) {
                var value = animation.animationObject.currentStep / animation.animationObject.numSteps;
            },
            onComplete: function(){
            	 
            }
					}
	    };

	ChartObjects[ctx.attr('id')] = new Chart(ctx, {
	    type: pietype,
	    data: chartDataConfig,
	    options: chartOptions 
	});
}