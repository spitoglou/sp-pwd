<?php
    $method = $_SERVER['REQUEST_METHOD'];
    $request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));

    if ($method<>'GET' or $request[0]<>'password' or count($request)<>2) {
        header("HTTP/1.0 400 Bad Request"); //400:Bad Request
        header('Content-type: application/json');
        die(json_encode(array('error'=>'Not a supported request by this web service')));
    }  else {
        $p=$request[1];
        $response=array();
        $response['checked-password']=$p;
        $score=0;
        $ul=0; //upper-lower case existence indicator
        $num=0; //number existence indicator
        $sc=0;  //special character existence indicator
        
        /*
        *   size matters!! so first we assess the 
        *   length of a password with top 60/100 in the initial scale for 
        *   passwords grater or equal of 12 chars 
        */
        
        if (strlen($p)>=4) {
            $score+=20;
        }
        if (strlen($p)>=8) {
            $score+=30;
        }
        if (strlen($p)>=12) {
            $score+=10;
        }
        
        /*
        *   We complete the initial assesment by checking the existence of
        *   uppercase and lowercase letters => +10/100
        *   numbers => +10/100 
        *   special characters => +20/100
        */
        
        if (preg_match("/[a-z]/", $p) and preg_match("/[A-Z]/", $p)) 
        {
            $score+=10;
            $response['contains-uppercase-lowercase']='Yes'; 
            $ul=1;
        }
        
        if (preg_match("/[0-9]/", $p)) 
        {
            $score+=10;
            $response['contains-numbers']='Yes';
            $num=1;
        }
        if (preg_match("/.[!,@,#,$,%,^,&,*,?,_,~,-,£,(,)]/", $p)) 
        {
            $score+=20;
            $response['contains-special-chars']='Yes';
            $sc=1; 
        }
        
        /*
        *   Then we make a second pass based on the concurrent 
        *   existence of all above character variations
        *   factors: ul=>30%, num=>30%, sc=>40%
        */
        
        $score=$score*($ul*0.3+$num*0.3+$sc*0.4);
        
        /*
        *   The last pass is based on the (unofficial) google
        *   password rating service (scores 1 to 4)
        */
        
        $googleResponse= file_get_contents("https://www.google.com/accounts/RatePassword?Passwd=$p");
        $response['google-rate']=$googleResponse;
        
        $score=$score*$googleResponse*0.25;
        $response['sp-score']=number_format($score,2);

        header('Content-type: application/json'); 
        die(json_encode($response));
    }
?>
