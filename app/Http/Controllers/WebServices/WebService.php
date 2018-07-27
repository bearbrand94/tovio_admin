<?php

namespace App\Http\Controllers\WebServices;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Response;
use Mail;
use Validator;
use File;
use AWS;
use Config;
use Image;
use DateTime;
use Session;

use App\Models\Order;
use Aws\S3\S3Client;
use Aws\Credentials\Credentials;

class WebService extends BaseController
{
	private static $email;
	private static $subject;
	private static $site;
	private static $username;
	private static $name;
	private static $checkout;
	private static $totalOrder;
	private static $orderId;

    public function index(){
    	echo 'Web Service Called';
    }

	public function show($name){
	    return view('hello',array('name' => $name));
	}

	public function createErrorMessage($message, $errorCode){
		$result = [ "payload"=>'',
				    "error_msg"=>$message,
					"code"=>$errorCode
					];
		return response()->json($result, $errorCode);
	}

	public function createSuccessMessage($payload){
		$result = [ "payload"=>$payload,
				    "error_msg"=>'',
					"code"=>200
					];
		return response()->json($result);
	}

	public static function guid(){
		if (function_exists('com_create_guid')){
			return com_create_guid();
		}else{
			mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
			$charid = strtoupper(md5(uniqid(rand(), true)));
			$hyphen = chr(45);// "-"
			$uuid = chr(123)// "{"
					.substr($charid, 0, 8).$hyphen
					.substr($charid, 8, 4).$hyphen
					.substr($charid,12, 4).$hyphen
					.substr($charid,16, 4).$hyphen
					.substr($charid,20,12)
					.chr(125);// "}"
			return $uuid;
		}
	}

	public function sendMail($email,$subject,$site,$username,$name,$checkout,$totalOrder,$orderId){
		$this->email=$email;
		$this->subject=$subject;
		$this->site=$site;
		$this->name=$name;
		$this->username=$username;
		$this->checkout=$checkout;
		$this->totalOrder=$totalOrder;
		$this->orderId=$orderId;

		Mail::send($this->site,['username'=>$this->username,'name'=>$this->name,'productOrder' => $this->checkout,'order_id' => $this->orderId,'totalOrder' =>$this->totalOrder], function($message) use ($email)
      	{
      		$message->from('conecplus@gmail.com','Conectplus');
        	$message->to($email)->subject($this->subject);
      	});

	}

    public function sendMailContactUs($email,$subject,$site,$pertanyaan){
        $this->email=$email;
        $this->subject='Pesan baru dari shafira web';
        $this->site=$site;
        $this->pertanyaan=$pertanyaan;

        Mail::send($this->site,['pertanyaan'=>$this->pertanyaan], function($message) use ($email,$pertanyaan)
        {
            $message->from($pertanyaan['email'],$pertanyaan['name']);
            $message->to($email)->subject($this->subject);
        });

    }

    public function uploadPDF($pdf,$category){
        // Credential S3
        $credentials = new Credentials(getenv('S3_KEY'), getenv('S3_SECRET'));

        $s3 = new S3Client([
            'version' => 'latest',
            'region'  => 'ap-southeast-1',
            'credentials' => $credentials
        ]);
        $aws_path = Config::get('aws_path');
        $randomName = Order::createBookingCode('PDF');
        //$name = (null !== $pdf->getClientOriginalName())?$pdf->getClientOriginalName():$randomName;
        $name = $pdf->getClientOriginalName();
        // $name = $randomName.'.pdf';
        $key_pdf = $aws_path['brosur_path'].$name;
            
        try {
            $result_s3 = $s3->putObject([
                'Bucket' => $aws_path['bucket'],
                'Key'    => $key_pdf,
                'ContentType' => 'application/pdf',
                'ContentDisposition' => 'inline',
                'SourceFile' => $pdf,
                'ACL'         => 'public-read'
            ]);
            return $result_s3['ObjectURL'];

        } catch (S3Exception $e){
            return Response::json($e->getMessage(), 400);
        }
    }

	public function uploadS3($file,$category){
		$type = $file->getMimeType();
        // check on the file type first
        if (!($type == "image/jpeg" || $type == "image/png" || $type == "image/jpg")){
            return Response::json("please use jpeg or png format", 400);
        }

        $extension = File::extension($file->getClientOriginalName());

        $guid = $this->guid();
        $filenameThumbnail =  $guid."-thumbnail.".$extension;
        $filenameMedium =  $guid."-medium.".$extension;
        $filenameOriginal =  $guid."-original.".$extension;

        // orientate image on iphone
        $img = Image::make($file);

        // createOriginalImage
        $edited_image_path = $file->getRealPath()."original";
        $edited_image_path = str_replace(".tmp", "", $edited_image_path);
        $file_orientate = $img->orientate();
        $imageOri = $file_orientate->save($edited_image_path);
        $file_path_ori = $edited_image_path;
               
        // createMediumImage
        $edited_image_path = $file->getRealPath()."medium";
        $edited_image_path = str_replace(".tmp", "", $edited_image_path);
        $file_orientate = $img->orientate();
        $image800 = $file_orientate->fit(800, 800)->save($edited_image_path);
        $file_path_medium = $edited_image_path;

        // createThumbnail
        $edited_image_path = $file->getRealPath()."thumbnail";
        $edited_image_path = str_replace(".tmp", "", $edited_image_path);
        $file_orientate = $img->orientate();
        $image300 = $file_orientate->fit(300, 300)->save($edited_image_path);
        $file_path_thumbnail = $edited_image_path;

        // Credential S3
        $credentials = new Credentials(getenv('S3_KEY'), getenv('S3_SECRET'));

        $s3 = new S3Client([
            'version' => 'latest',
            'region'  => 'ap-southeast-1',
            'credentials' => $credentials
        ]);
        $aws_path = Config::get('aws_path');
        switch ($category) {
        	case 'user':
        		$keyThumbnail = $aws_path['user_path'].$filenameThumbnail;
		        $keyMedium = $aws_path['user_path'].$filenameMedium;
		        $keyOriginal = $aws_path['user_path'].$filenameOriginal;
        		break;
            case 'news':
                $keyThumbnail = $aws_path['post_path'].$filenameThumbnail;
                $keyMedium = $aws_path['post_path'].$filenameMedium;
                $keyOriginal = $aws_path['post_path'].$filenameOriginal;
                break;
        	default:
        		# code...
        		break;
        }
        
        
        try {
            $result_s3_thumbnail = $s3->putObject([
                'Bucket' => $aws_path['bucket'],
                'Key'    => $keyThumbnail,
                'ContentType' => $type,
                'SourceFile' => $file_path_thumbnail,
                'ACL'         => 'public-read'
            ]);

            $result_s3_medium = $s3->putObject([
                'Bucket' => $aws_path['bucket'],
                'Key'    => $keyMedium,
                'ContentType' => $type,
                'SourceFile' => $file_path_medium,
                'ACL'         => 'public-read'
            ]);

            $result_s3 = $s3->putObject([
                'Bucket' => $aws_path['bucket'],
                'Key'    => $keyOriginal,
                'ContentType' => $type,
                'SourceFile' => $file_path_ori,
                'ACL'         => 'public-read'
            ]);
            $data = [];
            $data['url_original'] = $result_s3['ObjectURL'];
            $data['url_medium'] = $result_s3_medium['ObjectURL'];
            $data['url_thumbnail'] = $result_s3_thumbnail['ObjectURL'];
            return $data;

        } catch (S3Exception $e){
            return Response::json($e->getMessage(), 400);
        }
	}

}
