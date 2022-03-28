export default{
    ordinal(n){
        var s = ["th", "st", "nd", "rd"];
        var v = n%100;
        return n + (s[(v-20)%10] || s[v] || s[0]);
    },
     dateH(d){
            const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun","Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
            let newDate = new Date(d);
            console.log(newDate);

            return this.lastwo(newDate.getDay()) + ' ' + monthNames[newDate.getMonth()] + ' ' + newDate.getFullYear() + ' ' +newDate.getHours() + ":" + this.lastwo(newDate.getMinutes());
        },
        lastwo(str){
            return ('0' + str).slice(-2);
        },
}
