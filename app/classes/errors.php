<?php
	class errors {


        public function result($success, $error, $modal, $title = '', $message = '', $items = array(),$params = array()) {
            $alert = new alerts();
            $token = Token::generate();
			$html = $alert->alert('success');
            switch($success) {
                case true:
                    $result['token'] = $token;
                    if($title == ''){
                        $title = 'Success';
                    } else {
                        $title = $title;
                    }
                    if($message == ''){
                        $message = 'update successful';
                    } else {
                        $message = $message;
                    }
                    $error = 0;


                    break;
                case false:
                    $html = $alert->alert('warning');
                    switch($error) {
                        case 'other':
                            $error = '9999';
                            if($title == ''){
                                $title = $error.': Undefined error';
                            } else {
                                $title = $error.': '.$title;
                            }
                            if($message == ''){
                                $message = 'There was an error that has not been defined!';
                            } else {
                                $message = $message;
                            }
                            $html = $alert->alert('orange');
                        break;
                        case 'user':
                            $error = '0001';


                            if($title == ''){
                                $title = $error.': User error';
                            } else {
                                $title = $error.': '.$title;
                            }
                            if($message == ''){
                                $message = 'User not logged in!';
                            } else {
                                $message = $message;
                            }
                            $html = $alert->alert('danger');
                        break;
                        case 'user_permission':
                            $error = '0011';


                            if($title == ''){
                                $title = $error.': Insufficient Priveledges';
                            } else {
                                $title = $error.': '.$title;
                            }
                            if($message == ''){
                                $message = 'You do not have permission to do this!';
                            } else {
                                $message = $message;
                            }
                            $html = $alert->alert('danger');
                        break;
                        case 'input':
                            $error = '0002';


                            if($title == ''){
                                $title = $error.': No input';
                            } else {
                                $title = $error.': '.$title;
                            }
                            if($message == ''){
                                $message = 'There appears to be no data input!';
                            } else {
                                $message = $message;
                            }
                            $html = $alert->alert('danger');
                            //echo $html;
                        break;
                        case 'token':
                            $error = '0003';
                            $title = 'Invalid Token';
                            $message = 'Please Refresh the page and try again!';
                            if($title == ''){
                                $title = $error.': No input';
                            } else {
                                $title = $error.': '.$title;
                            }
                            if($message == ''){
                                $message = 'There appears to be no data input!';
                            } else {
                                $message = $message;
                            }
                            $html = $alert->alert('warning');

                        break;
                        case 'validation':
                            $result['token'] = $token;
                            $error = '0004';


                            if($title == ''){
                                $title = $error.': Validation error';
                            } else {
                                $title = $error.': '.$title;
                            }
                            if($message == ''){
                                $message = 'Some of the information you entered did not meet the requirements!';
                            } else {
                                $message = $message;
                            }
                            $html = $alert->alert('warning');
                            json_encode($items);
                        break;
                        case 'insert':
                            $error = '0005';


                            if($title == ''){
                                $title = $error.': Database Error';
                            } else {
                                $title = $error.': '.$title;
                            }
                            if($message == ''){
                                $message = 'There was a problem inserting the data';
                            } else {
                                $message = $message;
                            }
                            $html = $alert->alert('danger');
                            json_encode($items);
                        break;
                        case 'update':
                            $result['token'] = $token;
                            $error = '0006';

                            if($title == ''){
                                $title = $error.': Database Error';
                            } else {
                                $title = $error.': '.$title;
                            }
                            if($message == ''){
                                $message = 'Unable to update records!';
                            } else {
                                $message = $message;
                            }
                            $html = $alert->alert('danger');
                            json_encode($items);
                        break;
                        case 'dataload':
                            $error = '2020';
                            if($title == ''){
                                $title = $error.': Database import Failed!';
                            } else {
                                $title = $error.': '.$title;
                            }
                            if($message == ''){
                                $message = 'File was uploaded but data not included';
                            } else {
                                $message = $message;
                            }
                            $html = $alert->alert('danger');

                        break;
                        case 'upload':
                            $result['token'] = $token;
                            $error = '5003';
                            if($title == ''){
                                $title = $error.': File upload';
                            } else {
                                $title = $error.': '.$title;
                            }
                            if($message == ''){
                                $message = 'File was not saved';
                            } else {
                                $message = $message;
                            }
                            $html = $alert->alert('danger');
                        break;
                        case 'filename':
                            $result['token'] = $token;
                            $error = '5002';
                            if($title == ''){
                                $title = $error.': File name error!';
                            } else {
                                $title = $error.': '.$title;
                            }
                            if($message == ''){
                                $message = 'The file uploaded did not have the expected file name!';
                            } else {
                                $message = $message;
                            }
                            $html = $alert->alert('danger');
                        break;
                        case 'filecheck':
                            $result['token'] = $token;
                            $error = '5002';
                            if($title == ''){
                                $title = $error.': File was not uploaded!';
                            } else {
                                $title = $error.': '.$title;
                            }
                            if($message == ''){
                                $message = 'The file uploaded did not have the expected file size!';
                            } else {
                                $message = $message;
                            }
                            $html = $alert->alert('danger');
                        break;
                        case 'userassigned':
                            $result['token'] = $token;
                            $error = '0007';
                            if($title == ''){
                                $title = $error.': Item not actioned!';
                            } else {
                                $title = $error.': '.$title;
                            }
                            if($message == ''){
                                $message = 'The action cannot be completed, this item is assigned to another user!';
                            } else {
                                $message = $message;
                            }
                            $html = $alert->alert('warning');
                        break;
                    }
                    break;
            }
        foreach($items as $k => $v){
            $result[$k] = $v;
        }
        $result['error'] = $error;
        $result['success'] = $success;
        $result['modal'] = $modal;
        $result['htm'] = $html;
        $result['title'] = $title;
        $result['message'] = $message;
        $result['items'] = $items;
        $result['data'] = $params;

        return $result;
    }
}
?>
