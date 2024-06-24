let securityManager = {

  preventXSSAttacks: function(input){
    const lt = /</g,
        gt = />/g,
        ap = /'/g,
        ic = /"/g;
    try {
        return input.toString()
            .replace(lt, "&lt;")
            .replace(gt, "&gt;")
            .replace(ap, "&#39;")
            .replace(ic, "&#34;");
    } catch (err) {
        return '';
    }
  }

}
