<?php

namespace ClebinGames\DotMapParser;

/**
 * Dot Map Parser
 * Chris Owen 2022
 * 
 * (Very) simple tool to read the best bits of a z88dk .map file into
 * an easier-to-read text file
 */
class App
{
    const VERSION = '0.1';
    const SEPARATOR = "\r\n";

    // errors
    public static $error = false;
    public static $errorDetails = [];

    public static $inputFilename;
    public static $outputFilename;

    public static $strInput;
    public static $strOutput = '';

    public static $valuesToParse = [
        '__CODE_head',
        '__CODE_END_tail',
        '__BSS_END_tail',
        '__BANK_0_END_tail',
        '__BANK_1_END_tail',
        '__BANK_3_END_tail',
        '__BANK_4_END_tail',
        '__BANK_6_END_tail',
    ];

    /**
     * Run the tool
     */
    public static function Run($options)
    {
        self::OutputIntro();

        // check input/output filenames
        if (
            !isset($options['input']) ||
            !isset($options['output'])
        ) {
            self::AddError('Missing required arguments.');
            return false;
        }

        // read the map file into a string
        self::$inputFilename = $options['input'];
        self::$outputFilename = $options['output'];

        if (isset($options['values-file'])) {
            self::ReadValuesFile($options['values-file']);
        }

        self::$strInput = file_get_contents(self::$inputFilename);

        self::ParseInput();

        // write to output file
        if (strlen(self::$strOutput) > 0) {
            file_put_contents(self::$outputFilename, self::$strOutput);
        }
    }

    /** Read values file */
    public static function ReadValuesFile($filename)
    {
        $contents = file_get_contents($filename);
        $line = strtok($contents, self::SEPARATOR);

        self::$valuesToParse = [];

        while ($line !== false) {

            if ($line != '') {
                self::$valuesToParse[] = trim($line);
            }
            $line = strtok(self::SEPARATOR);
        }

        // print_r(self::$valuesToParse);
    }

    /**
     * Parse the map file
     */
    public static function ParseInput()
    {
        $outLine = '';
        $line = strtok(self::$strInput, self::SEPARATOR);

        while ($line !== false) {
            // loop through looking for info
            foreach (self::$valuesToParse as $info) {

                if ($info != '' && strpos($line, $info) === 0) {

                    $outLine = substr($line, 0, strpos($line, ';') - 1);
                    self::$strOutput .= $outLine . CR;
                }
            }

            $line = strtok(self::SEPARATOR);
        }
    }

    /**
     * Output intro text on command line
     */
    public static function OutputIntro()
    {
        echo '* Dot Map Parser v' . self::VERSION . ' - Chris Owen 2022 *' . CR;
    }

    /**
     * Add to errors list
     */
    public static function AddError($error)
    {
        self::$error = true;
        self::$errorDetails[] = ltrim($error, '.');
    }

    /**
     * Did an error occur?
     */
    public static function DidErrorOccur()
    {
        return self::$error;
    }
}
