function tt(en, cn) {
    return document.getElementById('language').getAttribute('content') === 'en-US' ? en : cn;
}

window.location.changeParam = function(name, value) {
    var pattern = new RegExp('(\\?|\\&)('+name+'=).*?(&|$)'),
        url = this.href,
        newUrl = this.href;

    if(url.search(pattern)>=0){
        newUrl = url.replace(pattern,'$1$2' + value + '$3');
    }
    else{
        newUrl = newUrl + (newUrl.indexOf('?') > 0 ? '&' : '?') + name + '=' + value;
    }

    this.href = newUrl;
};

String.prototype.getWidth = function(font)  
{  
    var size = calculateSize(this, {
       font: 'Arial',
       fontSize: font+'px'
    });
    return size.width;
};

Date.prototype.format = function(fmt)   
{ //author: meizz   
  var o = {   
    "M+" : this.getMonth()+1,                 //月份   
    "d+" : this.getDate(),                    //日   
    "h+" : this.getHours(),                   //小时   
    "m+" : this.getMinutes(),                 //分   
    "s+" : this.getSeconds(),                 //秒   
    "q+" : Math.floor((this.getMonth()+3)/3), //季度   
    "S"  : this.getMilliseconds()             //毫秒   
  };   
  if(/(y+)/.test(fmt))   
    fmt=fmt.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));   
  for(var k in o)   
    if(new RegExp("("+ k +")").test(fmt))   
  fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));   
  return fmt;   
};