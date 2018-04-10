<?php

namespace Volcagnomes\PHPMpg321;

use Volcagnomes\PHPMpg321\Exceptions\InvalidParameterException;
use Volcagnomes\PHPMpg321\Exceptions\UnreadableFileException;
use Volcagnomes\PHPMpg321\Exceptions\EmptyPlayException;

/**
 * Main class for the PHP Mpg321 wrapper.
 */
class Mpg321
{
    private $PlayMode = 'normal';
    private $files = array();

    /**
     * Set a new play mode (normal, shuffle, random).
     *
     * @param string $newMode
     *
     * @throws InvalidParameterException
     *
     * @return self
     */
    public function setPlayMode($newMode)
    {
        if (!in_array($newMode, ['normal', 'shuffle', 'random'])) {
            throw new InvalidParameterException($newMode.' isn\'t a valid play mode !');
        }
        $this->PlayMode = $newMode;

        return $this;
    }

    /**
     * Sets a list of files, folders or URLs to play. If files or folders, they must be readable.
     *
     * @param string|array $files
     *
     * @throws UnreadableFileException
     *
     * @return \Volcagnomes\PHPMpg321\Mpg321
     */
    public function setFiles($files)
    {
        //Clean up previous files entries
        $this->files = [];

        //Convert argument to array if necessary
        if (!is_array($files)) {
            $files = [$files];
        }

        //If files or folders, check if readable
        foreach ($files as $file) {
            if (!preg_match('@^(http:|https:|ftp:|ftps:)@i', $file)) {
                //If it's a file or a folder
                if (is_dir($file)) {
                    //it's a dir, scan files and add them
                    $folder_files = scandir($file);
                    foreach ($folder_files as $folder_file) {
                        //Adding all supported files to the list
                        if (preg_match('@(.mp3|.wav)$@i', $folder_file)) {
                            $this->files[] = $file.'/'.$folder_file;
                        }
                    }
                } else {
                    if (!is_readable($file)) {
                        throw new UnreadableFileException($file.' is not a readable file');
                    }
                    //Add to the list
                    $this->files[] = $file;
                }
            } else {
                //Set the files
                $this->files[] = $file;
            }
        }

        return $this;
    }

    /**
     * Returns the command-line option for the playmode.
     *
     * @return null|string
     */
    public function getPlayModeOption()
    {
        switch ($this->PlayMode) {
            case 'normal':
                return null;
            case 'shuffle':
                return '--shuffle';
            case 'random':
                return '--random';
            default:
                return null;
        }
    }

    /**
     * Returns the command-line option for the files.
     *
     * @return null|string
     */
    public function getFilesOption()
    {
        if (empty($this->files)) {
            return null;
        }

        //Encapsulate files in double-quote for command-line sake
        return '"'.join('" "', $this->files).'"';
    }
    
    /**
     * Concatenates all parameters and returns a ready-to-use parameter line
     * Used just before running the shell command 
     * @throws EmptyPlayException
     * @return string
     */
    public function getAllOptions()
    {
        //Initialize
        $params = [];
        //Get the play mode
        $params[] = $this->getPlayModeOption();
        
        //Get the medias to play
        $files = $this->getFilesOption();
        //At this point, if no files have been selected, there is a problem
        if ($files == null){
            throw new EmptyPlayException();
        }
        $params[] = $files;
        
        //@todo : add other options here
        
        return join(' ', $params);
    }
}
