/**
 *
 * @param t 总价
 * @param f 首付比例
 * @param y 年份
 * @param r 利率
 * @param x 税
 */
function calculate(t,f,y,r,x){
    //总金额
    var total = parseFloat(!t ? 0 : t);

    //首付比例
    var firstRate = parseInt(!f ? 0 : f);

    //按揭年份
    var years = parseInt(!y ? 0 : y);

    //利率
    var rate = parseFloat(!r ? 0 : r);
    
    //税费
    var tax = parseFloat(!x ? 0 : x.replace(',',''));

    this.result = function(){

        if(firstRate < 10 || firstRate > 80){
            return;
        }

        if(rate < 1 || rate > 10){
            return;
        }

        if(years < 5 || years > 30){
            return;
        }

        //贷款月数
        var months = years * 12;

        //月利率
        var ir = rate / (100 * 12);

        //首付
        var firstPay = total * (firstRate / 100);

        //要贷款的金额
        var principal = total - firstPay;

        //每月税费
        var taxMonth = tax / 12;

        //月供
        var monthPay = principal * (ir/(1- Math.pow((1 + ir),(0 - months)))) + taxMonth;

        var totalPay = monthPay * 12;

        return{
            totalPrice : total,//总价
            firstPay : Math.ceil(firstPay),//首付
            tax : tax,//税
            principal : Math.ceil(principal),//贷款金额
            monthPay : Math.ceil(monthPay),//月供
            totalPay : Math.ceil(totalPay)//总共还款金额
        };
    };
};/**
 * Created by wujia on 16/4/18.
 */
