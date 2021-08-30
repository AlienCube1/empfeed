<?php
namespace EmpFeed\Core;

use EmpFeed\Core\Utilities\Util;

class View{
    // Array of all views that are generated
    protected static $views = [

    ];
    
    /**
     * Function that renders views that were requested
     * Function takes in a single param
     * 
     * @param $viewPath Name of the field that needs to be rendered
     * 
     * @return html code from a file that is located in the ./views/ folder (TODO: Add .env file that can be changed were views are located)
     */
    

    public static function Make($viewPath, $deletable = false){
            // Construct the view from the place the user has specified.
            ob_start();
            $viewLocation = dirname(__DIR__, 2) . '/src/' . Util::getEnv('default_view_location');
            $viewFile = fopen($viewLocation.$viewPath, 'r');
            echo fread($viewFile, filesize($viewLocation.$viewPath));    
            fclose($viewFile);
            
        
    }

    public static function destroy($viewToDestroy){
        ob_end_clean();
        foreach(self::$views as $view){
            if($view != $viewToDestroy){
                self::Make($view);
            }
        }
      
    }
    

}

?>