$('.rets-calculator .div-input').keydown(function (e) {
    return e.keyCode !== 13;
});
$('.rets-calculator .calculate-item').click(function () {
    $input = $(this).find('.div-input');
    if (document.activeElement !== $input.get(0)) {
        $input.focus();
        selectText($input.get(0));
    }
});

window.chartPie = function (dataset, monthPay) {
    var ele = $('#charts-result').get(0);
    var pie = d3.layout.pie();
    var arc = d3.svg.arc()  
        .innerRadius(40)  
        .outerRadius(60);
    var svg = d3.select(ele);
    svg.selectAll('*').remove();
    var names = ['本金利息', '房产锐'];

    svg.style('width', $(ele).width() + 'px')
        .style('height', $(ele).height() + 'px');

    var tooltip = d3.select('body')  
        .append('div')  
        .attr('class', 'tooltip')  
        .style('opacity', 0.0);

    var arcs = svg.selectAll('g')  
        .data(pie(dataset))  
        .enter()  
        .append('g')
        .attr('transform', 'translate(90, 65)')
        .attr('stroke', '#fff')
        .attr('stroke-width', 1.2)
        .on('mouseover', function (d, i) {
            d3.select(this).attr('stroke', colors[i]);
            tooltip.html(names[i] + ': $' + (dataset[i].toFixed(2)))
                .style('left', (d3.event.pageX) + 'px')  
                .style('top', (d3.event.pageY + 20) + 'px')  
                .style('opacity', 1.0);  
        })
        .on('mousemove', function (d, i) {
            tooltip.style("left", (d3.event.pageX) + "px")  
                .style("top", (d3.event.pageY + 20) + "px");
        })
        .on('mouseout', function () {
            d3.select(this).attr('stroke', '#fff');
            tooltip.style('opacity', 0.0);
        });

    var colors = ['#99bd2a', '#f66c6c']; 

    arcs.append('path')  
        .attr('fill',function(d,i){  
            return colors[i];  
        })
        .attr('d',function(d){  
            return arc(d);
        });

    var legendGroup = svg.append('g')
        .attr('class', 'legend')
        .attr('transform', 'translate(15, 136)');

    var legendIems = legendGroup.selectAll('g.item')
        .data(dataset)
        .enter()
        .append('g')
        .attr('fill', function(d, i){
            return '#777';
        })
        .attr('transform', function (d, i) {
            return 'translate(' + (i * 90) + ', 10)'
        });

        legendIems.append('rect')
            .style('width', '10px')
            .style('height', '14px')
            .style('rx', '3px')
            .style('ry', '3px')
            .style('fill', function(d, i){
                return colors[i];
            });

        var texts = legendIems.append('text')
            .attr('x', '15')
            .attr('y', '12');

        texts.append('tspan')
            .text(function(d, i){
                return names[i];
            });

    var mpGroup = svg.append('g')
        .attr('class', 'legend')
        .attr('transform', 'translate(90, 55)')
        .attr('text-anchor', 'middle');

    mpGroup.append('text')
        .text('月供')
        .attr('font-size', '14px')
        .attr('fill', '#777');

    mpGroup.append('text')
        .text('$' + monthPay)
        .attr('transform', 'translate(0, 24)')
        .attr('font-size', '18px');
};

window.executeCalculate = function (pt, is_english) {
    var d = {
        ma: document.getElementById('ma').innerText,
        dp: document.getElementById('dp').innerText,
        mt: document.getElementById('mt').innerText,
        ir: document.getElementById('ir').innerText,
        pt: pt,
    }

    if (! is_english) {
        d.ma = d.ma * 10000;
    }

    var calcu = new calculate(d.ma, d.dp, d.mt, d.ir, d.pt);
    if (result = calcu.result()) {
        chartPie([result.monthPay - result.tax / 12, result.tax], result.monthPay);
    }
};

function selectText(text) {
    if (document.body.createTextRange) {
        var range = document.body.createTextRange();
        range.moveToElementText(text);
        range.select();
    } else if (window.getSelection) {
        var selection = window.getSelection();
        var range = document.createRange();
        range.selectNodeContents(text);
        selection.removeAllRanges();
        selection.addRange(range);
        /*if(selection.setBaseAndExtent){
            selection.setBaseAndExtent(text, 0, text, 1);
        }*/
    } else {
        alert("none");
    }
}
