<?php

namespace PeclExtension\Process;

use PeclExtension\PeclExtension;
use PeclExtension\Process\Winkill\Kernel\Interface\Exception;
use PeclExtension\Process\Winkill\Winkill;

class Process
{
    /**
     * @var mixed
     */
    protected static mixed $output;

    /**
     * @param string $cmd
     * @param string $output_file Creates a log file
     * @param string $pid_file
     * @param bool $mergestd_error Hide CLI issues
     * @param bool $bg Show complete error in terminal
     * 
     * @return self
     */
    public static function executeCommand(
        string $cmd,
        string $output_file = "",
        string $pid_file = "",
        bool $mergestd_error = true,
        bool $bg = false
    ): mixed {
        $fullcmd = $cmd;
        if (strlen($output_file) > 0) $fullcmd .= " >> " . $output_file;
        if ($mergestd_error) $fullcmd .= " 2>&1";

        if ($bg) {
            $fullcmd = "nohup " . $fullcmd . " &";
            if (strlen($pid_file)) $fullcmd .= " echo $! > " . $pid_file;
        } else {
            if (strlen($pid_file) > 0) $fullcmd .= "; echo $$ > " . $pid_file;
        }

        self::$output = shell_exec($fullcmd);

        return new static;
    }

    /**
     * @param string $process
     * 
     * @return true|null
     */
    public static function status(string $process, string $status = 'get'): ?true
    {
        try {
            $winkill = new Winkill();
            $processes = $winkill->scan();
            $processes->where('process_name', '=', $process . '.exe');

            if ($status == 'get') {
                $selected = $processes->get();
            }

            if ($status == 'kill') {
                $selected = $processes->kill();
            }

            if (is_array($selected)) {
                return true;
            }
        } catch (Exception | \Throwable $throwable) {
            die($throwable->getMessage());
        }

        return null;
    }

    /**
     * Check for a current process by filename
     * @param $file[optional] Filename
     * @return Boolean
     */
    public static function processExists($file = false)
    {
        $exists     = false;
        $file       = $file ? $file : __FILE__;

        // Check if file is in process list
        exec("ps -C $file -o pid=", $pids);
        if (count($pids) > 1) {
            $exists = true;
        }
        return $exists;
    }

    /**
     * @return mixed
     */
    public function getOutput(): mixed
    {
        return self::$output;
    }

    /**
     * @param string $command
     * 
     * @return true
     */
    public static function execInBackground(string $command, string $log_file): true
    {
        if (PeclExtension::getOS() == "Windows") {
            //windows
            pclose(popen("start /B " . $command . " 1> $log_file 2>&1", "r"));
        } else {
            //linux
            self::executeCommand($command . " 1> $log_file 2>&1");
        }

        return true;
    }
}
