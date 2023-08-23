$(document).ready(function(){
                $("#frmloading").hide();
                $("#tabelinput").hide();

                $('select').selectstyle();
                
                var data = 
                    "txtpage="+$("#txtpage").val()+
                    "&txtperpage="+$("#txtperpage").val()+
                    "&txtdepartemen="+$("#txtdepartemen").val()+
                    "&txtprosedur="+$("#txtprosedur").val()+
                    "";
                $.ajax({
                    url: "frmview.php",
                    type: "POST",
                    data: data,
                    cache: false,
                    success: function (html) {
                        $("#frmcontent").html(html);
                        $("#frmbody").slideDown('slow');
                    }
                });
                
            });

            function enterfind(event){
                //alert(event.keyCode);
                if(event.keyCode==13){
                    findclick();
                }else{
                    return ;
                }
            };

            function findclick(){
                var data = 
                    "txtpage="+$("#txtpage").val()+
                    "&txtperpage="+$("#txtperpage").val()+
                    "&txtdepartemen="+$("#txtdepartemen").val()+
                    "&txtprocparent="+$("#txtprocparent").val()+
                    "&txtprosedur="+$("#txtprosedur").val()+
                    "";
                $("#frmbody").slideUp('slow',function(){
                    $("#frmloading").slideDown('slow',function(){
                        $.ajax({
                            url: "frmview.php",
                            type: "POST",
                            data: data,
                            cache: false,
                            success: function (html) {
                                $("#frmcontent").html(html);
                                $("#frmbody").slideDown('slow',function(){
                                    $("#frmloading").slideUp('slow');
                                });
                            }
                        });
                    });
                });
                //alert('text');
            };

            function addnewclick(){
                //        clearinput();
                $("#intxtmode").val('add');
                $("#mode").text('Add New');
                $("#inbln").attr('disabled','');   
                $("#inthn").attr('disabled','');   
                $("#inproduk").attr('disabled',''); 
                $("#displaySCAN").html("");
                $("#tabelview").fadeOut("slow",function(){
                    searchclick();
                    $("#tabelinput").fadeIn("slow");
                });

            };

            function deleteclick(){
                $("input:checked").each(function () {
                    //alert('coba');
                    $("#intxtmode").val('delete');
                    var data = "intxtmode=delete&inid="+$(this).val()+
                        "";
                    $.ajax({
                        url: "actfrm.php",
                        type: "POST",
                        data: data,
                        cache: false,
                        success: function(data) {
                            alert(data);
                        }
                    });
                });
                findclick();
            };

            function editclick(){
                var n = $("input:checked").length;
                if (n>1){
                    alert('Just one select for edit');
                }else if(n==0){
                    alert('You must select One for edit');
                }else{
                    var data = "intxtmode=getedit&inid="+$("input:checked").val();
                    $.ajax({
                        url: "actfrm.php",
                        type: "POST",
                        data: data,
                        cache: false,
                        success: function(html){
                            $("#intxtmode").val('edit');
                            $("#mode").text('Edit');
                            $("#areaedit").html(html);
                            $("#tabelview").fadeOut("slow",function(){
                                findparent();
                                setinput();
                                $("#tabelinput").fadeIn("slow");
                            });
                        }
                    })
                }
            };

            function exportclick(){
                //        var randomnumber=Math.floor(Math.random()*11)
                //        var exptype = $("#exporttype").val();
                //        switch (exptype)
                //        {
                //        case 'grd':
                //          $("#formexport").attr('action', 'frmviewgrid.php');
                //          $("#formexport").submit();
                //          break;
                //        case 'pdf':
                $("#txtxidx").val($("#vxid").val());
                $("#formexport").attr('action', 'frmviewpdf.php');
                $("#formexport").submit();
                //          alert($("#vxid").val());
                //          break;
                //        case 'xls':
                //          $("#formexport").attr('action', 'frmviewxls.php');
                //          $("#formexport").submit();
                //          break;
                //        case 'csv':
                //          $("#formexport").attr('action', 'frmviewcsv.php');
                //          $("#formexport").submit();
                //          break;
                //        case 'txt':
                //          $("#formexport").attr('action', 'frmviewtxt.php');
                //          $("#formexport").submit();
                //          break;
                //        default:
                //          alert('Unidentyfication Type');
                //        }
            };

            function setinput(){
                $("#inid").val($("#getID").text());
                $("#indepartemen").val($("#getDEPARTEMEN").text());
                $("#inprocparent").val($("#getIDPARENT").text());
                $("#inprosedur").val($("#getPROSEDUR").text());
                
                displayPicture($("#getNAMAGAMBAR").text(), $("#getJMLFILE").text());
            };

            function clearinput(){
                $("#picture").val("");
                $("#txtnamafile").val("");
                $("#txtjmlfile").val("");
                $("#inprosedur").val("");
                $("#indepartemen").val("");
                $("#displaySCAN").html("");
            };

            function saveclick(){
//                if($("#txtnamafile").val() == ""){
//                    alert("Pilih File PDF terlebih dahulu !!!");
//                    return;
//                }
                
                if($("#inprosedur").val() == ""){
                    alert("Isi nama prosedur !!!");
                    return;
                }
                
                if($("#indepartemen").val() == ""){
                    alert("pilih departemen !!!");
                    return;
                }
                
                var data = "intxtmode="+$("#intxtmode").val()+
                    "&inid="+$("#inid").val()+
                    "&txtnamafile="+$("#txtnamafile").val()+
                    "&txtjmlfile="+ $("#txtjmlfile").val()+
                    "&inprosedur="+ $("#inprosedur").val()+
                    "&inprocparent="+ $("#inprocparent").val()+
                    "&indepartemen="+ $("#indepartemen").val()+
                    "";
                
                $.ajax({
                    url : "actfrm.php",
                    type: "POST",
                    data: data,
                    cache: false,
                    success: function(data){
                        alert(data);
                        if($("#intxtmode").val() == "edit"){
                            cancelclick();
                            hapusImage($("#namafilebefore").val(), $("#jmlfilebefore").val());
                        }else{
                            clearinput();
                        }
                    }
                })
            };

            function cancelclick(){
                if($("#intxtmode").val() != "edit"){
                    if($("#picture").val() != ""){
                        hapusImage($("#txtnamafile").val(), $("#txtjmlfile").val());
                        $("#txtnamafile").val("");
                        $("#txtjmlfile").val("");
                        $("#picture").val("");
                    }
                }
                
                $("#intxtmode").val('');
                $("#mode").text('');
                $("#tabelinput").fadeOut("slow",function(){
                    clearinput();
                    $("#tabelview").fadeIn("slow",findclick());
                });
                $("#displaySCAN").attr('disabled','');
            };
            
            function hapusImage(picture, jmlfile){
                var data = "intxtmode=deletejpg&namagambar="+picture +
                    "&jmlpage=" + jmlfile +
                    "";
                $.ajax({
                    url : "actfrm.php",
                    type: "POST",
                    data: data,
                    cache: false,
                    success: function(data){
                        //alert(data);
                    }
                });
            }

            function searchclick(){
                if ($("#areasearch").is(":hidden")) {
                    $("#areasearch").slideDown("slow");
                } else {
                    $("#areasearch").slideUp("slow");
                }
            };

            function showpage(page){
                $("#txtpage").val(page);
                findclick();
            }

            function prevpage(){
                var n = eval($("#txtpage").val())-1 ;
                if (n >= 1) {
                    $("#txtpage").val(n);
                    findclick();
                }
            }

            function nextpage(){
                var n = eval($("#txtpage").val())+1 ;
                if (eval(n)<=eval($("#jumpage").val())){
                    $("#txtpage").val(n);
                    findclick();
                }
            }

            function startUpload(){
                //                $("#uploaded-picture").show();
                //                $("#uploaded-picture").html("loading...");
            }

            function displayPicture(picture, jmlpage){
                if($("#intxtmode").val() == "edit"){
                    $("#namafilebefore").val($("#txtnamafile").val());
                    $("#jmlfilebefore").val($("#txtjmlfile").val());
                }
                
                var data = "intxtmode=viewgambar&namagambar="+picture+"&jmlpage="+jmlpage;
//                alert(data);
                $.ajax({
                    url: "actfrm.php",
                    type: "POST",
                    data: data,
                    cache: false,
                    success: function(img){
                        $("#displaySCAN").html(img);
                        $("#txtnamafile").val(picture);
                        $("#txtjmlfile").val(jmlpage);
                    }
                })
            }
    
            function cancel_gambar(){
                var data = "intxtmode=delete_cancel&inxgambar="+$("#inxgambar").val()+"";
                $.ajax({
                    url: "actfrm.php",
                    type: "POST",
                    data: data,
                    cache: false,
                    success: function(data) {
                        $("#displaySCAN").html('Loading...');
                        $("#formupload").submit();
                    }
                });
            }
    
            function submitclick(){    
                cancel_gambar();
            }
    
            function prasubmit(){
                $("#picture").val('');
            }
            
            function findparent(){
                var data = "intxtmode=findparent&indepartemen="+$("#indepartemen").val()+"";
                $.ajax({
                    url: "actfrm.php",
                    type: "POST",
                    data: data,
                    cache: false,
                    success: function(html) {
                        $("#inprocparent").html(html);
                    }
                });
            }
            
            function pilihdepartemen(){
                var data = "intxtmode=findparent&indepartemen="+$("#txtdepartemen").val()+"";
                $.ajax({
                    url: "actfrm.php",
                    type: "POST",
                    data: data,
                    cache: false,
                    success: function(html) {
                        $("#txtprocparent").html(html);
                    }
                });
            }



            function MyValidDate(dateString){
                var validformat=/^\d{1,2}\/\d{1,2}\/\d{4}$/ //Basic check for format validity
                if (!validformat.test(dateString)){
                    return ''
                }else{ //Detailed check for valid date ranges
                    var dayfield=dateString.substring(0,2);
                    var monthfield=dateString.substring(3,5);
                    var yearfield=dateString.substring(6,10);
                    var MyNewDate = monthfield + "/" + dayfield + "/" + yearfield;
                    if (checkValidDate(MyNewDate)==true){
                        var SQLNewDate = yearfield + "/" + monthfield + "/" + dayfield;
                        return SQLNewDate;
                    }else{
                        return '';
                    }
                }
            }

            function checkValidDate(dateStr) {
                // dateStr must be of format month day year with either slashes
                // or dashes separating the parts. Some minor changes would have
                // to be made to use day month year or another format.
                // This function returns True if the date is valid.
                var slash1 = dateStr.indexOf("/");
                if (slash1 == -1) { slash1 = dateStr.indexOf("-"); }
                // if no slashes or dashes, invalid date
                if (slash1 == -1) { return false; }
                var dateMonth = dateStr.substring(0, slash1)
                var dateMonthAndYear = dateStr.substring(slash1+1, dateStr.length);
                var slash2 = dateMonthAndYear.indexOf("/");
                if (slash2 == -1) { slash2 = dateMonthAndYear.indexOf("-"); }
                // if not a second slash or dash, invalid date
                if (slash2 == -1) { return false; }
                var dateDay = dateMonthAndYear.substring(0, slash2);
                var dateYear = dateMonthAndYear.substring(slash2+1, dateMonthAndYear.length);
                if ( (dateMonth == "") || (dateDay == "") || (dateYear == "") ) { return false; }
                // if any non-digits in the month, invalid date
                for (var x=0; x < dateMonth.length; x++) {
                    var digit = dateMonth.substring(x, x+1);
                    if ((digit < "0") || (digit > "9")) { return false; }
                }
                // convert the text month to a number
                var numMonth = 0;
                for (var x=0; x < dateMonth.length; x++) {
                    digit = dateMonth.substring(x, x+1);
                    numMonth *= 10;
                    numMonth += parseInt(digit);
                }
                if ((numMonth <= 0) || (numMonth > 12)) { return false; }
                // if any non-digits in the day, invalid date
                for (var x=0; x < dateDay.length; x++) {
                    digit = dateDay.substring(x, x+1);
                    if ((digit < "0") || (digit > "9")) { return false; }
                }
                // convert the text day to a number
                var numDay = 0;
                for (var x=0; x < dateDay.length; x++) {
                    digit = dateDay.substring(x, x+1);
                    numDay *= 10;
                    numDay += parseInt(digit);
                }
                if ((numDay <= 0) || (numDay > 31)) { return false; }
                // February can't be greater than 29 (leap year calculation comes later)
                if ((numMonth == 2) && (numDay > 29)) { return false; }
                // check for months with only 30 days
                if ((numMonth == 4) || (numMonth == 6) || (numMonth == 9) || (numMonth == 11)) {
                    if (numDay > 30) { return false; }
                }
                // if any non-digits in the year, invalid date
                for (var x=0; x < dateYear.length; x++) {
                    digit = dateYear.substring(x, x+1);
                    if ((digit < "0") || (digit > "9")) { return false; }
                }
                // convert the text year to a number
                var numYear = 0;
                for (var x=0; x < dateYear.length; x++) {
                    digit = dateYear.substring(x, x+1);
                    numYear *= 10;
                    numYear += parseInt(digit);
                }
                // Year must be a 2-digit year or a 4-digit year
                if ( (dateYear.length != 2) && (dateYear.length != 4) ) { return false; }
                // if 2-digit year, use 50 as a pivot date
                if ( (numYear < 50) && (dateYear.length == 2) ) { numYear += 2000; }
                if ( (numYear < 100) && (dateYear.length == 2) ) { numYear += 1900; }
                if ((numYear <= 0) || (numYear > 9999)) { return false; }
                // check for leap year if the month and day is Feb 29
                if ((numMonth == 2) && (numDay == 29)) {
                    var div4 = numYear % 4;
                    var div100 = numYear % 100;
                    var div400 = numYear % 400;
                    // if not divisible by 4, then not a leap year so Feb 29 is invalid
                    if (div4 != 0) { return false; }
                    // at this point, year is divisible by 4. So if year is divisible by
                    // 100 and not 400, then it's not a leap year so Feb 29 is invalid
                    if ((div100 == 0) && (div400 != 0)) { return false; }
                }
                // date is valid
                return true;
            }

            function null2zero(data){
                var hasil = 0 ;
                if(data==''){
                    hasil = 0 ;
                }else{
                    hasil = data;
                }
                return hasil;
            }

            function replaceAll(txt, replace, with_this) {
                return txt.replace(new RegExp(replace, 'g'),with_this);
            }

            function NumberFormat(nStr, inD, outD, sep)
            {
                nStr += '';
                var dpos = nStr.indexOf(inD);
                var nStrEnd = '';
                if (dpos != -1) {
                    nStrEnd = outD + nStr.substring(dpos + 1, nStr.length);
                    nStr = nStr.substring(0, dpos);
                }
                var rgx = /(\d+)(\d{3})/;
                while (rgx.test(nStr)) {
                    nStr = nStr.replace(rgx, '$1' + sep + '$2');
                }
                return nStr + nStrEnd;
            }

            function JsNumberFormat(nStr)
            {
                return NumberFormat(nStr, '.', '.', ',');
            }

            function JsUnNumberFormat(nStr)
            {
                return replaceAll(nStr, ',', '');
            }

            function LPad(ContentToSize,PadLength,PadChar){
                var PaddedString=ContentToSize.toString();
                var i = 0 ;
                for(i=ContentToSize.length+1;i<=PadLength;i++)
                {
                    PaddedString=PadChar+PaddedString;
                }
                return PaddedString;
            }

            function display_number(obj){
                var xobj = $(obj).eq(0) ;
                var xval = JsUnNumberFormat(xobj.val());
                xobj.val(JsNumberFormat(xval));
            }
            
            
            function openImage(picture, jmlPage){
                
                var data = "intxtmode=viewgambar2&namagambar="+picture+"&jmlpage="+jmlPage;
                $.ajax({
                    url: "actfrm.php",
                    type: "POST",
                    data: data,
                    cache: false,
                    success: function(img){
                        $("#dialog-image").html(img);
                        $("#view-dialog").dialog("open");
                    }
                });
            }