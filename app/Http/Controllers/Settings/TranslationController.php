<?php 

namespace App\Http\Controllers\Settings;

use App;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use File;
use Lang;


class TranslationController extends Controller
{
    
    private $lang = '';
    private $file;
    private $dir;
    private $key;
    private $value;
    private $path;
    private $arrayLang = array();

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    { 

    }

    /**
     * @param null
     * @return html
     */
    public function index()
    {   
        return view('settings.translation.language');
    }


   /**
     * @param null
     * @return html
     */
    public function create()
    {   
        $allLang =  \App\Dexlib\Locale::getAllLang(); 
        return view('settings.translation.create', compact('allLang'));
    }

      /**
     * @param null
     * @return html
     */
    public function store(Request $request)
    {   
         $lang = $request->input('language');
         $path = base_path().'/resources/lang/'.$lang;
         if(File::isDirectory($path)) return redirect()->back()->withErrors([__('application.Language already exist!')]);

         File::makeDirectory($path, $mode = 0777, true, true);

         return redirect()->back()->with('status', __('application.language created successfully.'));
    }

    /**
     * @param null
     * @return html
     */
    public function edit($lang = 'en',   $dirName = 'main' , $fileName = null)
    {   
        $this->lang = $lang;
        if(!File::isDirectory(base_path().'/resources/lang/'.$this->lang)) return redirect()->route('translation.edit', ['lang' => $this->lang])->withErrors([__('application.Language not found!')]);
        //Language List
        $language = \App\Dexlib\Locale::getActiveLang();
        $langName = $language[$lang];

        // Directory List
        $directoriesInFolder = File::directories('../resources/lang/en');
        $directories = [];
        $directories[] = [
            'name' =>  'main',
            'code' =>  'main',
            'is_directory' => true
        ];
        foreach ($directoriesInFolder as $key => $value) {
            $directories[] = [
                'name' =>  basename( $value),
                'code' =>  basename( $value),
                'is_directory' => true
            ];
        }   
        //Direcotry End

        //File Lists
        if($dirName != 'main'){
            $filesInFolder = File::allFiles('../resources/lang/en/'.$dirName);
        }else{
            $filesInFolder = File::allFiles('../resources/lang/en');
        }     
   
        $files = [];    
        foreach ($filesInFolder as $key => $value) {
           $data = [
                'file_path' => $value->getPathName(),
                'file_name' => $value->getFileName(),                
                'code' => pathinfo($value->getFileName(), PATHINFO_FILENAME),
                'name' => ucfirst(pathinfo($value->getFileName(), PATHINFO_FILENAME))
           ];  
           $files[pathinfo($value->getFileName(), PATHINFO_FILENAME)] = $data;
        }
      
        $contents = []; 

        if(!empty($fileName)){

            if($dirName != 'main'){ 
                $this->path = base_path().'/resources/lang/'.$this->lang.'/'.$dirName.'/'.$fileName.'.php';
                $englishMainPath = base_path().'/resources/lang/en/'.$dirName.'/'.$fileName.'.php';
            }else{
                $this->path = base_path().'/resources/lang/'.$this->lang.'/'.$fileName.'.php';
                $englishMainPath = base_path().'/resources/lang/en/'.$fileName.'.php';
            }
            
            if(!File::exists($englishMainPath)) return redirect()->route('translation.edit', ['lang' => $this->lang])->withErrors([__('application.File not found!')]);
            $this->dir = $dirName;
            $this->file = $fileName;
            $this->read();
            $contents = $this->arrayLang; 
            
        }        

        return view('settings.translation.list', compact('langName', 'lang', 'files', 'contents', 'fileName', 'directories', 'dirName'));
    }


     
  
    /**
     * @param Request
     * @return Redirect
     */
    public function save(Request $request) 
    {    
        $this->lang = $request->input('lang');
        $this->file =  $request->input('fileName');
        $this->dir = $request->input('dirName');        
        $request->input('fileName');
        if($this->dir != 'main'){ 
            $this->path = base_path().'/resources/lang/'.$this->lang.'/'.$this->dir.'/'.$this->file.'.php';
        }else{
            $this->path = base_path().'/resources/lang/'.$this->lang.'/'.$this->file.'.php';
        }
        $this->read(true);
      
        $payload = $request->all(); 
        $contents = $payload['translationValues']; 

        $content = "<?php \n\n return [ \n ";
        foreach ($this->arrayLang as $key => $value) 
        {
            if(!is_array($value)){ //Single Array                         
                if(!empty($contents[$key])){
                    $value = $contents[$key];
                    $content .= "\t'".$key."' => '".$value."',\n";
                }                  
            
            }else{
                //MultiDimention Array
                $contentParentChild = "\t [ \n ";
                foreach ($value as $pckey => $pcvalue) 
                {
                    if(!is_array($pcvalue)){
                        $contentParentChild .= "\t\t'".$pckey."' => '".$pcvalue."',\n";
                    }else{
                        $contentChild = "\t [ \n ";
                        foreach ($pcvalue as $ckey => $cvalue)  { 
                            if(!is_array($cvalue)){
                                $contentChild .= "\t\t\t'".$ckey."' => '".$cvalue."',\n";
                            }
                        }
                        $contentChild .= "\t],\n";  
                        if(is_array((array) $contentChild)){
                           $contentParentChild .= "\t'".$pckey."' => ".$contentChild."";
                        }else{
                           $contentParentChild .= "\t'".$pckey."' => '".$contentChild."',\n"; 
                        }       
                    }
                }

                $contentParentChild .= "\t],\n";      

                if(is_array((array) $contentParentChild)){
                   $content .= "\t'".$key."' => ".$contentParentChild."";
                }else{
                   $content .= "\t'".$key."' => '".$contentParentChild."',\n"; 
                }             

            }            
                        
        }

        $content .= "];";  

        file_put_contents($this->path, $content);

       return redirect()->back()->with('success', __('application.Translations updated successfully.'));
    }

     /**
     * @param null
     * @return null
     */
    private function read($isOriginal = false) 
    {   
        $this->arrayLang = array();
        if ($this->lang != '') {
            
            $content = []; 
           
            if($this->dir != 'main'){ 
                $englishMainPath = base_path().'/resources/lang/en/'.$this->dir.'/'.$this->file.'.php';
            }else{
                $englishMainPath = base_path().'/resources/lang/en/'.$this->file.'.php';
            }

            $content = File::getRequire($englishMainPath);
         
            if(!$isOriginal && is_array($content)){   
                $path =  base_path().'/resources/lang/'.$this->lang.'/'.$this->dir;
                if(!File::isDirectory($path) && $this->dir != 'main'){
                    File::makeDirectory($path, 0777, true, true);
                }
                if($this->dir != 'main'){ 
                    $this->path = base_path().'/resources/lang/'.$this->lang.'/'.$this->dir.'/'.$this->file.'.php';
                }else{
                    $this->path = base_path().'/resources/lang/'.$this->lang.'/'.$this->file.'.php';
                }

                if(!File::exists($this->path)){                   
                    file_put_contents( $this->path, ''); 
                    $tranContent = File::getRequire($this->path);           
                }else{
                    $tranContent = File::getRequire($this->path);
                }
                
                $i = 1;
                foreach ($content as $key => $value) {
                   if(!is_array( $value)){
                        $content[$key] = [
                            'id' => $i,
                            'original' => $value,
                            'translate' => !empty($tranContent[$key]) ? $tranContent[$key] : '',
                            'is_translate' => !empty($tranContent[$key]) ? true : false
                        ];
                        
                        $i++;
                    }else{
                        unset($content[$key]);
                    }
                }
            }

            $this->arrayLang = $content;
            if (gettype($this->arrayLang) == 'string') $this->arrayLang = array();
        
        }

    }


   /**
     * @param array
     * @return json
     */
    public function translate(Request $request) 
    {   

        $data =  $request->all();
        $yandex_key = '';
        $googlemaps_key ='AIzaSyBRvPgYBOxpkyEWXxxqErte182C245iy84';
        $transText = '';

        if(!empty($yandex_key)){
            //yandex
            $url = "https://translate.yandex.net/api/v1.5/tr.json/translate?key=" . $yandex_key . "&text=%TEXT%&lang=en-%TARGET%";
            $url = str_replace("%TEXT%", urlencode($data["original"]), $url);
            $url = str_replace("%TARGET%", urlencode($data["lang"]), $url);

            $response = json_decode(file_get_contents($url), true);
            $transText = '';
            if($response['code'] == 200){
                $transText = $response['text'][0];
                $transText = str_replace("'", "", $transText);
            }
        }

        if(empty($transText)){
         
            /** Try with google */
            $url = "https://translate.googleapis.com/translate_a/single?client=gtx&sl=en&tl=%TARGET%&dt=t&q=%TEXT%";
            $url = str_replace("%TEXT%", urlencode($data["original"]), $url);
            $url = str_replace("%TARGET%", urlencode($data["lang"]), $url);

            $response = json_decode(file_get_contents($url));
            $transText = $response[0][0][0];
            $transText = str_replace("'", "", $transText);     
        }

        return response()->json(['response'=> true, 'text' => $transText, 'id' => $data['id']]);

    }

}