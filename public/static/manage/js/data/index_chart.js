$(function () {
    $.ajax({
        url : '/manage/income/getChartsInfo',
        type : 'POST',
        dataType : 'json',
        success : function (res) {
            console.log(res);
            var xAxisData =[],incomeData = [];
            if(!!res.code){
                xAxisData = res.data.xAxisData;
                incomeData = res.data.countAll;
                $('.yesBonus').html(res.data.yesBonus);
                $('.monthBonus').html(res.data.monthBonus);
            }
            var myChart = echarts.init(document.getElementById("incomeChart"));
            var option = getOption(xAxisData,incomeData);
            myChart.setOption(option);
            window.onresize = myChart.resize;

        },
        error : function (res) {
            console.log(res);
        }

    });
    var getOption = function (xAxisData,incomeData) {
        return {
            tooltip: {
                trigger: 'axis',
                axisPointer: {
                    type: 'shadow'
                }
            },
            legend: {
                data:['收入金额']
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            yAxis: {
                type: 'value'
            },
            calculable : true,
            xAxis : [
                {
                    type : 'category',
                    offset: 10,
                    nameTextStyle: {
                        fontSize: 15
                    },
                    data : xAxisData
                }
            ],
            series : [
                {
                    name:'收入金额',
                    type:'bar',
                    barWidth: 14,
                    barGap: 10,
                    smooth: true,
                    label: {
                        normal: {
                            show: true,
                            position: 'top',
                            textStyle: {
                                color: '#ff7f50',
                                fontSize: 13
                            }
                        }
                    },
                    itemStyle: {
                        emphasis: {
                            barBorderRadius: 7
                        },
                        normal: {
                            barBorderRadius: 7,
                            color: new echarts.graphic.LinearGradient(
                                0, 0, 0, 1,
                                [
                                    {offset: 0, color: '#3977E6'},
                                    {offset: 1, color: '#0ae'}
                                ]
                            )
                        }
                    },
                    markLine : {
                        data : [
                            {type : 'average', name : '平均值'}
                        ]
                    },
                    data : incomeData
                }
            ]
        };
    };

});
