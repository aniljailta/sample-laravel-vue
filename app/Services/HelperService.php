<?php

namespace App\Services;

class HelperService
{

    /**
     * List of loaded helpers
     *
     * @var array
     * @access protected
     */
    protected static $helpers = array();

    public static function load($helpers = array())
    {
        foreach (self::prepFileName($helpers, 'Helper') as $helper)
        {
            if (isset(self::$helpers[$helper]))
            {
                continue;
            }

            $ext_helper = app_path().'/Helpers/'.$helper.'.php';

            // Is this a helper extension request?
            if (file_exists($ext_helper))
            {
                $base_helper = app_path().'/Helpers/'.$helper.'.php';

                if ( ! file_exists($base_helper))
                {
                    continue;
                }

                include_once($ext_helper);
                include_once($base_helper);

                self::$helpers[$helper] = TRUE;
                continue;
            }
        }
    }

    /**
     * Prep filename
     *
     * This function preps the name of various items to make loading them more reliable.
     *
     * @param	mixed
     * @param 	string
     * @return	array
     */
    protected static function prepFileName($filename, $extension)
    {
        if ( ! is_array($filename))
        {
            return array((str_replace('.php', '', str_replace($extension, '', $filename)).$extension));
        }
        else
        {
            foreach ($filename as $key => $val)
            {
                $filename[$key] = (str_replace('.php', '', str_replace($extension, '', $val)).$extension);
            }

            return $filename;
        }
    }
}
