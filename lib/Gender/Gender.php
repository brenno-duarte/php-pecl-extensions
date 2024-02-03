<?php

namespace PeclPolyfill\Gender;

/**
 * Class Gender ported from pecl/gender 1.1.0
 * @package Gender
 */
class Gender
{
    const IS_FEMALE = 70;
    const IS_MOSTLY_FEMALE = 102;
    const IS_MALE = 77;
    const IS_MOSTLY_MALE = 109;
    const IS_UNISEX_NAME = 63;
    const IS_A_COUPLE = 67;
    const NAME_NOT_FOUND = 32;
    const ERROR_IN_NAME = 69;

    const ANY_COUNTRY = 0;
    const BRITAIN = 1;
    const IRELAND = 2;
    const USA = 3;
    const SPAIN = 4;
    const PORTUGAL = 5;
    const ITALY = 6;
    const MALTA = 7;
    const FRANCE = 8;
    const BELGIUM = 9;
    const LUXEMBOURG = 10;
    const NETHERLANDS = 11;
    const GERMANY = 12;
    const EAST_FRISIA = 13;
    const AUSTRIA = 14;
    const SWISS = 15;
    const ICELAND = 16;
    const DENMARK = 17;
    const NORWAY = 18;
    const SWEDEN = 19;
    const FINLAND = 20;
    const ESTONIA = 21;
    const LATVIA = 22;
    const LITHUANIA = 23;
    const POLAND = 24;
    const CZECH_REP = 25;
    const SLOVAKIA = 26;
    const HUNGARY = 27;
    const ROMANIA = 28;
    const BULGARIA = 29;
    const BOSNIA = 30;
    const CROATIA = 31;
    const KOSOVO = 32;
    const MACEDONIA = 33;
    const MONTENEGRO = 34;
    const SERBIA = 35;
    const SLOVENIA = 36;
    const ALBANIA = 37;
    const GREECE = 38;
    const RUSSIA = 39;
    const BELARUS = 40;
    const MOLDOVA = 41;
    const UKRAINE = 42;
    const ARMENIA = 43;
    const AZERBAIJAN = 44;
    const GEORGIA = 45;
    const KAZAKH_UZBEK = 46;
    const TURKEY = 47;
    const ARABIA = 48;
    const ISRAEL = 49;
    const CHINA = 50;
    const INDIA = 51;
    const JAPAN = 52;
    const KOREA = 53;
    const VIETNAM = 54;
    
    protected const COUNTRY_NAMES = [
        Gender::BRITAIN => ["UK", "Great Britain"],
        Gender::IRELAND => ["IRE", "Ireland"],
        Gender::USA => ["USA", "U.S.A."],
        
        Gender::ITALY => ["I", "Italy"],
        Gender::MALTA => ["M", "Malta"],
        Gender::PORTUGAL => ["P", "Portugal"],
        Gender::SPAIN => ["E", "Spain"],
        Gender::FRANCE => ["F", "France"],
        
        Gender::BELGIUM => ["B", "Belgium"],
        Gender::LUXEMBOURG => ["LUX", "Luxembourg"],
        Gender::NETHERLANDS => ["NL", "the Netherlands"],
        
        Gender::EAST_FRISIA => ["FRI", "East Frisia"],
        Gender::GERMANY => ["D", "Germany"],
        Gender::AUSTRIA => ["A", "Austria"],
        Gender::SWISS => ["CH", "Swiss"],
        
        Gender::ICELAND => ["ICE", "Iceland"],
        Gender::DENMARK => ["DK", "Denmark"],
        Gender::NORWAY => ["N", "Norway"],
        Gender::SWEDEN => ["S", "Sweden"],
        Gender::FINLAND => ["FIN", "Finland"],
        
        Gender::ESTONIA => ["EST", "Estonia"],
        Gender::LATVIA => ["LTV", "Latvia"],
        Gender::LITHUANIA => ["LIT", "Lithuania"],
        
        Gender::POLAND => ["PL", "Poland"],
        Gender::CZECH_REP => ["CZ", "Czech Republic"],
        Gender::SLOVAKIA => ["SK", "Slovakia"],
        Gender::HUNGARY => ["H", "Hungary"],
        Gender::ROMANIA => ["RO", "Romania"],
        Gender::BULGARIA => ["BG", "Bulgaria"],
        
        Gender::BOSNIA => ["BIH","Bosnia and Herzegovina"],
        Gender::CROATIA => ["CRO", "Croatia"],
        Gender::KOSOVO => ["KOS", "Kosovo"],
        Gender::MACEDONIA => ["MK", "Macedonia"],
        Gender::MONTENEGRO => ["MON", "Montenegro"],
        Gender::SERBIA => ["SER", "Serbia"],
        Gender::SLOVENIA => ["SLO", "Slovenia"],
        Gender::ALBANIA => ["AL", "Albania"],
        Gender::GREECE => ["GR", "Greece"],
        
        Gender::RUSSIA => ["RUS", "Russia"],
        Gender::BELARUS => ["BY", "Belarus"],
        Gender::MOLDOVA => ["MOL", "Moldova"],
        Gender::UKRAINE => ["UKR", "Ukraine"],
        Gender::ARMENIA => ["ARM", "Armenia"],
        Gender::AZERBAIJAN => ["AZE", "Azerbaijan"],
        Gender::GEORGIA => ["GEO", "Georgia"],
        Gender::KAZAKH_UZBEK => ["KAZ", "Kazakhstan/Uzbekistan"],
        
        Gender::TURKEY => ["TR", "Turkey"],
        Gender::ARABIA => ["AR", "Arabia/Persia"],
        Gender::ISRAEL => ["ISR", "Israel"],
        Gender::CHINA => ["CHN", "China"],
        Gender::INDIA => ["IND", "India/Sri Lanka"],
        Gender::JAPAN => ["JAP", "Japan"],
        Gender::KOREA => ["KOR", "Korea"],
        Gender::VIETNAM => ["VN", "Vietnam"],
    ];

    protected const CHECK_STRING = "# DO NOT CHANGE:   FILE-FORMAT DEFINITION-DATE = 2008-11-16 ";

    protected const DATA_NAME_POS =     3;
    protected const DATA_NAME_LENGTH =  26;
    protected const MAX_LINE_SIZE =     100;

    /**
     * @var resource
     */
    private $nameDataFile;

    /**
     * Create a Gender object optionally connecting to an external name dictionary. When no external database was given, compiled in data will be used.
     * @param null|string $dsn DSN to open.
     * @throws \InvalidArgumentException When $dsn is invalid.
     */
    public function __construct(?string $dsn = null)
    {
        if ($dsn === null) {
            $dsn = __DIR__ . DIRECTORY_SEPARATOR .'nam_dict.txt';
        }

        if (!$this->connect($dsn)) {
            throw new \RuntimeException("Unable to access data file: $dsn");
        }
    }

    /**
     * Connect to an external name dictionary.
     * @param string $dsn DSN to open.
     * @return bool Boolean as success or failure.
     * @throws \InvalidArgumentException When $dsn is invalid.
     */
    public function connect(string $dsn): bool
    {
        if ($this->nameDataFile !== null) {
            fclose($this->nameDataFile);
        }

        $this->nameDataFile = fopen($dsn, 'r');

        return $this->nameDataFile !== false;
    }

    /**
     * Returns the textual representation of a country from a Gender class constant.
     * @param int $country A country ID specified by a Gender\Gender class constant.
     * @return array Returns an array with the short and full names of the country.
     * @throws \InvalidArgumentException When $country is unknown.
     */
    public function country(int $country): array
    {
        if (!isset(self::COUNTRY_NAMES[$country])) {
            throw new \InvalidArgumentException("Unknown country: $country");
        }
        list($shortName, $longName) = self::COUNTRY_NAMES[$country];

        return [
            "country_short" => $shortName,
            "country" => $longName
        ];
    }

    /**
     * Get the gender of the name in a particular country.
     * @param string $name Name to check.
     * @param int $country Country id identified by Gender class constant.
     * @return int Returns gender of the name.
     */
    public function get(string $name, int $country = Gender::ANY_COUNTRY): int
    {
        return $this->searchName($name, $country);
        //Lookup $name, if found return result
        //Split on space, hyphen or dot, and lookup each part (including dots for abbreviations), combining results
    }

    protected function searchName(string $name, int $country): int
    {
        if(strlen($name) === 0) {
            return Gender::IS_UNISEX_NAME;
        }

        $position = $this->binarySearch($name);
        fseek($this->nameDataFile, $position);

        do {
            $line = fgets($this->nameDataFile, Gender::MAX_LINE_SIZE);

            $foundName = "";
            if (substr($line, 0, 1) !== '#') {
                $foundName = substr($line, Gender::DATA_NAME_POS, Gender::DATA_NAME_LENGTH);
            }

            return $foundName;

        } while(false);
        //If empty return UNISEX
        //if at least 2 chars and ends in dot set mode to abbr.
        // do a bin search on column 3, assuming it's 26 chars long
        // if result is <0 and == -10 do internal error, if just < 0 do name not found
        // seek to the pos

        //Enter restart loop
        //TODO: Track through the rest of internal_search (probably can ignore anything other than &searchGender,
        //      and share the logic as we get to the other methods
        //return ;
    }

    /**
     * Find the first occurrence of the $name in the data source.
     * @param string $name
     * @return int -10 on internal error, -1 on not found, or the position of the start of the line in the data source if found.
     */
    public function binarySearch(string $name): int
    {
        if (fseek($this->nameDataFile, 0) === -1) {
            throw new \RuntimeException("Unable to seek data file");
        }
        $line = fgets($this->nameDataFile, Gender::MAX_LINE_SIZE+1);
        if (strpos($line, Gender::CHECK_STRING) !== 0) {
            throw new \RuntimeException("Data file is not a known format");
        }
        $lineSize = ftell($this->nameDataFile);
        if (fseek($this->nameDataFile, 0, SEEK_END) === -1) {
            throw new \RuntimeException("Unable to seek data file");
        }
        $recordCount = ftell($this->nameDataFile)/$lineSize;

        $position1 = 0;
        $position2 = $recordCount;

        $i = -1;

        while ($position1 <= $position2) {
            $position = (int)(($position1 + $position2) / 2);
            fseek($this->nameDataFile, $position * $lineSize);
            $line = fgets($this->nameDataFile, Gender::MAX_LINE_SIZE+1);

            $foundName = '';

            if (substr($line, 0, 1) !== '#') {
                $foundName = substr($line, Gender::DATA_NAME_POS, Gender::DATA_NAME_LENGTH);
            }

            $i = $this->compareNames($name, $foundName, true);

            if ($i === 0) {
                if ($position1 == $position) {
                    return $position * $lineSize;
                }
                $position2 = $position;
            } elseif ($i < 0) {
                $position2 = $position - 1;
            } else {
                $position1 = $position + 1;
            }
        }

        return -1;
    }

    protected function compareNames(string $name, string $internalName, bool $shouldCompareAbbreviations): int
    {
        $nameScrubbed = $this->scrubName($name);
        $internalNameScrubbed = $this->scrubName($internalName);

        if ($shouldCompareAbbreviations && substr($nameScrubbed, -1) === '.') {
            return strpos($internalNameScrubbed, substr($nameScrubbed, 0, -1)) === 0;
        }

        return strcmp($nameScrubbed, $internalNameScrubbed);

        //If should cmp abr. check if $name ends in dot, and see if $name2 starts with $name up to dot
        //then use strcmp
    }

    protected function scrubName($name)
    {
        $umlautConversions = [
            "A",
            "A",
            "A",
            "A",
            "AA",
            "AE",
            "AE",
            "C",
            "D",
            "E",
            "E",
            "E",
            "E",
            "I",
            "I",
            "I",
            "I",
            "NH",
            "O",
            "O",
            "O",
            "O",
            "OE",
            "OE",
            "OE",
            "S",
            "SS",
            "TH",
            "U",
            "U",
            "U",
            "UE",
            "Y",
            "Y",
        ];
        return
        str_replace(
            str_split(mb_convert_encoding('ÀÁÂÃÅÄÆÇÐÈÉÊËÌÍÎÏÑÒÓÔÕÖØŒŠßÞÙÚÛÜÝŸ', 'latin1', 'utf8')),
            $umlautConversions,
            str_replace(
                str_split(mb_convert_encoding('àáâãåäæçðèéêëìíîïñòóôõöøœšßþùúûüýÿ', 'latin1', 'utf8')),
                $umlautConversions,
                strtoupper(
                    str_replace(
                        ["<>^,´'`~°/"],
                        '',
                        str_replace(
                            ['-', '+', "'"],
                            ['', '', '´'],
                            trim($name)
                        )
                    )
                )
            )
        );
    }

    /**
     * Check whether the name0 is a nick of the name1.
     * @param string $name0 Name to check.
     * @param string $name1 Name to check.
     * @param int $country Country id identified by Gender class constant. If omitted ANY_COUNTRY is used.
     * @return bool
     */
    public function isNick(string $name0, string $name1, int $country = Gender::ANY_COUNTRY): bool
    {
        throw new \RuntimeException("Unimplemented");
    }

    /**
     * Get similar names for the given name and country.
     * @param string $name Name to check.
     * @param int $country Country id identified by Gender class constant. If ommited ANY_COUNTRY is used.
     * @return string[]
     */
    public function similarNames(string $name, int $country = Gender::ANY_COUNTRY): array
    {
        //This will be a very long method
        throw new \RuntimeException("Unimplemented");
    }
}
