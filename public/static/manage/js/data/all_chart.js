$(function () {
    getChartInfo();
    $('.choseDate li').click(function () {
        var choseDate = $(this).data('val');
        getChartInfo(choseDate);
    });
});
var getChartInfo = function(choseDate){
    $.ajax({
        url : '/manage/income/getDetailChartsInfo',
        type : 'POST',
        data:{
            choseDate : choseDate
        },
        dataType : 'json',
        success : function (res) {
            console.log(res);
            var xAxisData =[],incomeData = [],outData =[],netIncomeData = [];
            if(!!res.code){
                xAxisData = res.data.xAxisData;
                incomeData = res.data.income;
                outData = res.data.out;
                netIncomeData = res.data.netIncome;
            }
            var myChart = echarts.init(document.getElementById("incomeChartAll"));
            var option = getOption(xAxisData,incomeData,outData,netIncomeData);

            myChart.setOption(option);
            window.onresize = myChart.resize;

        },
        error : function (res) {
            console.log(res);
        }
    });
};

var getOption = function (xAxisData,incomeData,outData,netIncomeData) {
    let colors = ['#5793f3', '#d14a61', '#675bba'];

    return {
        color: ['#5793f3', '#d14a61', '#675bba'],
        tooltip: {
            trigger: 'axis',
            axisPointer: {
                type: 'shadow'
            }
        },
        legend: {
            data: ['收入','支出','净收益'],
        },
        grid: {
            left: '10%',
            right: '10%',
            bottom: '10%',
        },
        xAxis: [
            {
                type: 'category',
                axisTick: { //坐标轴刻度相关设置。
                    show: false,
                },
                data: xAxisData
            }
        ],
        yAxis: [{
            type: 'value',
            name: '/元',
            nameGap: 10,
            axisLabel: {
                formatter: '{value}'
            }
        }],
        series: [
            {
                name: '收入',
                type: 'bar',
                label: { //图形上的文本标签，可用于说明图形的一些数据信息，比如值，名称等
                    normal: { //正常情况
                        show: true, //是否显示标签
                        position: 'inside', //标签的位置。
                        distance: 5, //距离图形元素的距离。当 position 为字符描述值（如 'top'、'insideRight'）时候有效。default: 5
                        rotate: 0, //标签旋转。从 -90 度到 90 度。正值是逆时针。
                    }
                },
                data: incomeData
            },
            {
                name: '支出',
                type: 'bar',
                stack: '总量',
                label: {
                    normal: {//正常情况
                        show: true, //是否显示标签
                        position: 'inside'
                    }
                },
                data: outData
            },
            {
                name: '净收益',
                type: 'line',
                stack: '总量', //数据堆叠，同个类目轴上系列配置相同的stack值可以堆叠放置。
                label: {
                    normal: {
                        show: true, //是否显示标签
                    }
                },
                data: netIncomeData
            }
        ]
    };
    return {
        color: colors,
        tooltip: {
            trigger: 'axis',
            axisPointer: {
                type: 'cross'
            }
        },
        grid: {
            right: '20%',
            bottom: '10%'
        },
        legend: {
            data:['收入','支出','净收益']
        },
        xAxis: [
            {
                type: 'category',
                axisTick: {
                    alignWithLabel: true
                },
                data: xAxisData
            }
        ],
        yAxis: [
            {
                type: 'value',
                name: '/元',
                // min: 0,
                // position: 'right',
                axisLine: {
                    lineStyle: {
                        color: colors[0]
                    }
                },
                axisLabel: {
                    formatter: '{value}'
                }
            },
            /*{
                type: 'value',
                name: '净收益/元',
                min: 0,
                position: 'right',
                offset: 80,
                axisLine: {
                    lineStyle: {
                        color: colors[1]
                    }
                },
                axisLabel: {
                    formatter: '{value}'
                }
            },*/
            /*{
                type: 'value',
                name: '收入/元',
                min: 0,
                position: 'left',
                axisLine: {
                    lineStyle: {
                        color: colors[2]
                    }
                },
                axisLabel: {
                    formatter: '{value}'
                }
            }*/
        ],
        series: [
            {
                name:'收入',
                type:'bar',
                data:incomeData
            },
            {
                name:'支出',
                type:'bar',
                yAxisIndex: 1,
                data:outData
            },
            {
                name:'净收益',
                type:'line',
                yAxisIndex: 2,
                data:netIncomeData
            }
        ]
    };
};

