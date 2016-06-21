<?php 
 session_start();
include ('db_config.php');
$l_username=trim($_POST['user_id']);
$l_pass=$_POST['user_psw'];

 if(!empty($l_username) && !empty($l_pass) ){
$l_password=md5($l_pass);
// login through emailid feature addedd on 4 december

$l_Emailid = $l_username; 
$array_Email=explode('@',$l_Emailid);
$l_UR_Emailid =$array_Email[0];
$l_UR_EmailidDomain = $array_Email[1]; 
$timezone = new DateTimeZone("Asia/Kolkata" );
$date = new DateTime();
$date->setTimezone($timezone );
$l_CM_DateTime = $date->format( 'YmdHi' );

// query for last login Date and Time
$l_query_users='select UR.UR_id,UR.IT_id, UR.PG_id, UR.UR_Type,UR.TM_id,UR.UR_Semester, UR.PR_id,UR.UR_USN, UR.UR_RegistrationStatus,UR.UR_PR_Type,UR.Org_id from  Users as UR where ( (UR.UR_Emailid="'.$l_UR_Emailid.'" and UR.UR_EmailidDomain="'.$l_UR_EmailidDomain.'") or UR.UR_id= "'.$l_username.'") and UR.UR_Khufiya ="'.$l_password.'"';

 $l_result_users = mysql_query($l_query_users) or die(mysql_error());
 
 $l_result_users_rowcount=mysql_num_rows($l_result_users);
    if($l_result_users_rowcount==1){
    
    
 
        $l_row_users = mysql_fetch_row($l_result_users);
        $l_regstatus = $l_row_users[8];
        if($l_regstatus=="C"){
            $_SESSION['g_TM_count']=$l_result_UR_teams_rowcount;
            $_SESSION['g_UR_id']=$l_row_users[0];
            $_SESSION['g_IT_id']=$l_row_users[1];
            $_SESSION['g_PG_id']=$l_row_users[2];
            $g_UR_Type = $l_row_users[3];
            $_SESSION['g_UR_Type']=$g_UR_Type;
            $_SESSION['g_TM_id']=$l_row_users[4];
            $_SESSION['g_Semester_id']=$l_row_users[5];
            $_SESSION['g_PR_id']=$l_row_users[6];
            $_SESSION['g_UR_PR_Type']=$l_row_users[9];
            $_SESSION['g_Org_id']=$l_row_users[10];
           
            $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

        $l_query_LoginDateTime='update Users set UR_LastLogin ='.$l_CM_DateTime.' where UR_id="'.$l_row_users[0].'"';
        mysql_query($l_query_LoginDateTime) or die(mysql_error());
        if($_SESSION['g_UR_Type']=='S' && $_SESSION['g_PR_id']!=NULL)
            {
           echo "<script> window.location.href = 'SHome.php'</script>";
             
            }
            else if($_SESSION['g_UR_Type']=='S' && $_SESSION['g_PR_id']==NULL)
            {
                 echo "<script> window.location.href = 'Projects.php'</script>";
               
            }
        else if($g_UR_Type == 'G') {
             //check for Guide
             echo "<script> window.location.href = 'GHome.php'</script>";  
            }
        else if($g_UR_Type == 'M') {
             //check for Mentor
             echo "<script> window.location.href = 'MHome.php'</script>";  
            }
        else if($g_UR_Type == 'A') {
             //check for College Admin
             echo "<script> window.location.href = 'AHome.php'</script>";  
            }
        else if($g_UR_Type == 'C') {
             //check for Company
             echo "<script> window.location.href = 'CHome.php'</script>";  
            }
        else if($g_UR_Type == 'PA') {
             //check for Admin User
             echo "<script> window.location.href = 'PAHome.php'</script>";  
            }
        else if($g_UR_Type == 'T') {
             //check for Admin
             echo "<script> window.location.href = 'THome.php'</script>";  
            }
        else{
               echo "<script> window.location.href = 'login.php'</script>";  
            }
        }
        else{
        $_SESSION['error'] = "<div class='alert alert-danger'> You have successfully registered but your admin has not yet confirmed. Please wait or contact your admin.</div>";
          echo "<script> window.location.href = 'login.php'</script>"; 
      }
    
     }
     else{
        $_SESSION['error'] = "<div class='alert alert-danger'>Please enter the correct User Id and Password.</div>";
          echo "<script> window.location.href = 'login.php'</script>"; 
      }
 
}
else{
    $_SESSION['error'] = "<div class='alert alert-danger'> You have not entered any User Id or Password</div>";
          echo "<script> window.location.href = 'login.php'</script>"; 
}
  
?>