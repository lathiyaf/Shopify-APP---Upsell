const host = process.env.MIX_APP_URL;
const apiEndPoint = host + '/api';
function init(){
    let params = getParams('royal-cart');
    let shopifyDomain = params['shop'];
    const Http = new XMLHttpRequest();
    const sendurl = apiEndPoint + '/welcome-push?shop=' + shopifyDomain;

    $isExistCookie = localStorage.getItem("rc-web-push-visited");
    // $isExistCookie = getCookie('rc-web-push-visited');
    if(typeof $isExistCookie == 'undefined' || $isExistCookie == null || $isExistCookie == ''){
        const xhr = new XMLHttpRequest(),
            method = "GET",
            url = sendurl;

        xhr.open(method, url, true);
        xhr.onreadystatechange = function () {
            // In local files, status is 0 upon success in Mozilla Firefox
            if(xhr.readyState === XMLHttpRequest.DONE) {
                var status = xhr.status;
                if (status === 0 || (status >= 200 && status < 400)) {
                    // The request has been completed successfully
                    let res = JSON.parse(xhr.responseText);
                    let data = res.data;
                    let welcome_push = res.welcome_push;
                    if( res.isSuccess ){
                        if( welcome_push != '' ){
                            let bodyData = document.getElementsByTagName('body')[0].innerHTML;
                            bodyData = welcome_push + bodyData;
                            document.getElementsByTagName('body')[0].innerHTML = bodyData;

                            // set lifetime cookie
                            // expiry = new Date();
                            // expiry.setTime(expiry.getTime()+(20*365*24*60*60*1000));
                            // // document.cookie = "rc-web-push-visited=yes; path=/";
                            // document.cookie = "rc-web-push-visited=yes; expires=" + expiry.toUTCString();
                            localStorage.setItem("rc-web-push-visited", "yes");

                            let closeBtn = document.getElementsByClassName('rc-close')[0];
                            console.log(closeBtn);
                            closeBtn.addEventListener("click", function(){
                                let ele = document.getElementsByClassName('rc-welcome-push')[0];
                                ele.parentNode.removeChild(ele);
                            });
                        }else{
                            console.log(data);
                        }
                    }else{
                        console.log(data);
                    }
                } else {
                    // Oh no! There has been an error with the request!
                }
            }
        };
        xhr.send();
    }
}

function getCookie(cname) {
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for(let i = 0; i <ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function getParams(script_name) {
    // Find all script tags
    var scripts = document.getElementsByTagName("script"); // Look through them trying to find ourselves

    for (var i = 0; i < scripts.length; i++) {
        if (scripts[i].src.indexOf("/" + script_name) > -1) {
            // Get an array of key=value strings of params
            var pa = scripts[i].src.split("?").pop().split("&"); // Split each key=value into array, the construct js object

            var p = {};

            for (var j = 0; j < pa.length; j++) {
                var kv = pa[j].split("=");
                p[kv[0]] = kv[1];
            }

            return p;
        }
    } // No scripts match


    return {};
}

window.onload = init();
