<?php
//身份證及居留證檢查程式
//舊版居留證第二碼為ABCD
//程式修改by sairwolf 
//源自作者:辛西亞．Cynthia
//連結:https://cynthiachuang.github.io/Check-Resident-Certificate-Number/
ini_set('default_charset', 'utf-8');
ini_set("display_errors",1);
ini_set("error_reporting","E_ALL & ~E_NOTICE"); 
if($_POST["check"])  {
    //pid_chk($pid,1);
    $pid = $_POST["pid"];   
}
?>
<script type="text/javascript">
function verifyId(id) {
    id = id.trim();
    id = id.toUpperCase();
    console.log(id)

    let conver = "ABCDEFGHJKLMNPQRSTUVXYWZIO"
    let weights = [1, 9, 8, 7, 6, 5, 4, 3, 2, 1, 1]

    <!-- 在 js 中遇到反斜線要跳脫，所以這邊用兩個反斜線 -->
    verification = id.match("^[A-Z]\\d{9}$")
    if(verification){  //身分證及新版居留證

        id = String(conver.indexOf(id[0]) + 10) + id.slice(1);

        checkSum = 0
        for (let i = 0; i < id.length; i++) {
            c = parseInt(id[i])
            w = weights[i]
            checkSum += c * w
        }

        return checkSum % 10 == 0
    }else {    //舊版居留證  
        if(!id.match("^[A-Z][ABCD]\\d{8}$"))
            return false

        id = String(conver.indexOf(id[0]) + 10) +  String((conver.indexOf(id[1]) + 10)%10) + id.slice(2);

        checkSum = 0
        for (let i = 0; i < id.length; i++) {
            c = parseInt(id[i])
            w = weights[i]
            checkSum += c * w
        }
        
        return checkSum % 10 == 0
    }

}


//console.log(verifyId("A123456789"));
console.log(verifyId("<?php echo $pid; ?>"));
</script>
身分證及居留證字號檢查程式<br>
<form method="post" name="aaa"><input maxlength="10" name="pid" required="required" size="15" type="text" placeholder="A123456789" /><input name="check" type="submit" value="檢查" />&nbsp;</form>

<?php
if($_POST["check"])  {
    $tf=pid_chk($pid,1);
    if($tf)
        echo "<br>正確!<br>";
    else 
        echo "<br>錯誤！<br>";
}

function pid_chk($id,$debug=0)  {
    $id = trim($id);
    $id = strtoupper($id);
    if($debug) echo "$id<br>";

    $conver = "ABCDEFGHJKLMNPQRSTUVXYWZIO";
    $weights = array(1, 9, 8, 7, 6, 5, 4, 3, 2, 1, 1);   

    $verification = preg_match("/^[A-Z]\d{9}$/",$id );
    if($verification)  {  //身分證及新版居留證

        //id = String(conver.indexOf(id[0]) + 10) + id.slice(1);
        if($debug) echo strpos($conver,$id[0])."<br>";
        $id = (string)(strpos($conver,$id[0])+10) . substr($id,1);
        if($debug) echo "$id<br>";

        $checkSum = 0;

        for ($i = 0; $i < strlen($id); $i++) {
            $c = (int)($id[$i]);
            $w = $weights[$i];
            $checkSum += $c * $w ;
            if($debug) echo "$i==>$c,$w,".$c*$w."<br>";
        }

        if($debug) echo $checkSum;

        return $checkSum % 10 == 0 ;



    }else {    //舊版居留證  
        if(!preg_match("/^[A-Z][ABCD]\d{8}$/",$id ))
            return false ;

        //id = String(conver.indexOf(id[0]) + 10) +  String((conver.indexOf(id[1]) + 10)%10) + id.slice(2);
        if($debug) echo strpos($conver,$id[0])."<br>";
        $id = (string)(strpos($conver,$id[0])+10) . (string)((strpos($conver,$id[1])+10)%10) . substr($id,2);
        if($debug) echo "$id<br>";

        $checkSum = 0;

        for ($i = 0; $i < strlen($id); $i++) {
            $c = (int)($id[$i]);
            $w = $weights[$i];
            $checkSum += $c * $w ;
            if($debug) echo "$i==>$c,$w,".$c*$w."<br>";
        }

        if($debug) echo $checkSum;

        return $checkSum % 10 == 0 ;
    }

}

?>