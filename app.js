const clipboardJS = new ClipboardJS('#copy-short-link');
 
const submitDOM = document.getElementById(&quotsubmit&quot);
const urlInput = document.getElementById(&quoturl-inp&quot);
const shortLinkWrapper = document.getElementsByClassName(&quotshort-link-wrapper&quot)[0];
 
 
submitDOM.addEventListener(&quotclick&quot, handlerSubmit);
 
 
function handlerSubmit() {
    if (!urlInput.checkValidity()) {
        alert(&quotآدرس داده شده معتبر نمی باشد .&quot);
        return false;
    }
 
    submitDOM.setAttribute(&quotdisabled&quot, &quotdisabled&quot);
    submitDOM.value = &quotدر حال دریافت لینک ..."
 
    funcPostRequest(function (xhr) {
        const response = xhr.currentTarget.response;
        let data = response.data;
 
        if(typeof data == &quotstring&quot) data = JSON.parse(data);
 
         
        window.alert(response.msg);
        if (data['link_short']) {
            shortLinkWrapper.classList.add(&quotactive&quot);
            submitDOM.removeAttribute(&quotdisabled&quot);
            submitDOM.value = &quotکوتاه کن"
            shortLinkWrapper.querySelector(&quot#short-link-copy&quot).value = location.origin + &quot?u=&quot+ data['link_short'];
        }
 
 
 
    }, function () {
        alert(&quotخطایی رخ داده برای جزئیات بیشتر تب console را باز کنید&quot)
        console.warn(&quot[XHR Error]&quot);
 
        submitDOM.removeAttribute(&quotdisabled&quot);
        submitDOM.value = &quotکوتاه کن"
    });
 
}
 
 
function funcPostRequest(cbOnload, cbOnerror) {
    const xhr = new XMLHttpRequest();
    xhr.responseType = &quotjson"
 
    const params = new FormData;
    params.append(&quotaction&quot, &quotsubmit&quot);
    params.append(&quotinp-url&quot, urlInput.value);
 
    xhr.open(&quotPOST&quot, location.origin + &quot/api.php&quot);
 
    xhr. = cbOnload;
    xhr. = cbOnerror;
 
    xhr.send(params);
}
