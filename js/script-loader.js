let preciseFunnels = {};
(() => {
    let pfxhr = new XMLHttpRequest()
    pfxhr.open("GET", pfrd_php_vars.sl_url)
    pfxhr.onload = () => {
        if (pfxhr.status === 200) {
            preciseFunnels.data = JSON.parse(pfxhr.responseText)
            if (preciseFunnels.data.jwt_token && preciseFunnels.data.script_url) {
                let tocScriptElement = document.createElement("script")
                tocScriptElement.async = 1
                tocScriptElement.src = preciseFunnels.data.script_url
                document.body.appendChild(tocScriptElement)
            }
        } else {
            console.log(pfxhr)
        }
    }
    pfxhr.send()
})()