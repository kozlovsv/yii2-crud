<?php /** @noinspection HtmlUnknownTag */

namespace kozlovsv\crud\components;

use Exception;
use Yii;
use yii\base\BaseObject;
use yii\db\ActiveQuery;
use yii\db\Query;

/**
 * Class ExportExcelXML
 * @package common\ext
 */
class ExportExcelXML extends BaseObject
{
    const TYPE_TEXT = 'text';
    const TYPE_NUMBER = 'number';
    const TYPE_HEADER = 'header';
    const TYPE_URL = 'url';
    const TYPE_DATE = 'date';
    const TYPE_DATETIME = 'datetime';
    const TYPE_TEXTNOWRAP = 'nowrap';


    /**
     * @var ActiveQuery|Query
     */
    public $query;

    /**
     * @var array
     */
    public $columns;

    /**
     * @var string
     */
    public $fileName = 'Экспорт';
    /**
     * @var mixed
     */
    public $documentTitle = 'Экспорт';
    /**
     * @var mixed
     */
    public $documentAuthor = 'Auto Book';
    /**
     * @var mixed
     */
    public $documentVersion = 1;

    /**
     * @var array
     */
    private $currentRow = [];

    /**
     * @var int
     */
    private $colCount;

    /**
     * @var int
     */
    private $rowCount;

    /**
     * @var int
     */
    public $limit = 8000;

    /**
     * @var bool
     */
    public $afterExit = true;

    /**
     * Sends the HTML headers to the client.
     * This is only necessary if the XML doc is to be delivered from the server
     * to the browser.
     */
    public function sendHeaders()
    {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $this->fileName . '.xls"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0,pre-check=0');
        header('Pragma: public');
    }

    public function openWriter()
    {
        $this->out('<?xml version="1.0"?>');
        $this->out('<?mso-application progid="Excel.Sheet"?>');
    }

    public function openWorkbook()
    {
        $this->out('<ss:Workbook xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">');
        $this->writeDocumentProperties();
        $this->writeStyles();
    }

    public function writeDocumentProperties()
    {
        $this->out('<ss:DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">');
        $this->out("<ss:Title>ExportGridViewToExcelXML.php{$this->documentTitle}</ss:Title>");
        $this->out('<ss:Created>' . date('Y-m-d') . 'T' . date('H:i:s') . 'Z' . '</ss:Created>');
        $this->out("<ss:Author>{$this->documentAuthor}</ss:Author>");
        $this->out("<ss:Version>{$this->documentVersion}</ss:Version>");
        $this->out('</ss:DocumentProperties>');
    }

    public function writeStyles()
    {
        $this->out('<ss:Styles>');
        //default style
        $this->out('<ss:Style ss:ID="Default" ss:Name="Normal"><ss:Font ss:Color="#000000"/><ss:Alignment ss:Vertical="Bottom"/></ss:Style>');
        //Text
        $this->out('<ss:Style ss:ID="Text">');
        $this->out('<ss:Alignment ss:Vertical="Top" ss:WrapText="1"/>');
        $this->writeBorderStyle();
        $this->out('</ss:Style>');
        //Number
        $this->out('<ss:Style ss:ID="Number">');
        $this->out('<ss:Alignment ss:Vertical="Top"/>');
        $this->writeBorderStyle();
        $this->out('<ss:NumberFormat ss:Format="#,##0"/>');
        $this->out('</ss:Style>');
        //Header
        $this->out('<ss:Style ss:ID="Header">');
        $this->writeBorderStyle();
        $this->out('<ss:Font ss:Color="Automatic" ss:Bold="1"/>');
        $this->out('<ss:Interior ss:Color="#C0C0C0" ss:Pattern="Solid"/>');
        $this->out('</ss:Style>');
        //Hyperlink style
        $this->out('<ss:Style ss:ID = "URL">');
        $this->out('<ss:Alignment ss:Vertical="Top" ss:WrapText="1"/>');
        $this->writeBorderStyle();
        $this->out('<ss:Font  ss:Color="#0000FF" ss:Underline="Single"/>');
        $this->out('</ss:Style>');
        //Date
        $this->out('<ss:Style ss:ID="Date">');
        $this->out('<ss:Alignment ss:Vertical="Top" ss:WrapText="1"/>');
        $this->writeBorderStyle();
        $this->out('<ss:NumberFormat ss:Format="Short Date"/>');
        $this->out('</ss:Style>');
        //DateTime
        $this->out('<ss:Style ss:ID = "DateTime">');
        $this->out('<ss:Alignment ss:Vertical="Top" ss:WrapText="1"/>');
        $this->writeBorderStyle();
        $this->out('<ss:NumberFormat ss:Format="dd.mm.yyyy h:mm"/>');
        $this->out('</ss:Style>');
        //TextNoWrap
        $this->out('<ss:Style ss:ID = "Text_No_Wrap">');
        $this->out('<ss:Alignment ss:Vertical="Top"/>');
        $this->writeBorderStyle();
        $this->out('</ss:Style>');
        $this->out('</ss:Styles>');
    }

    /**
     * @param string $name
     * @param null|array $attributes
     */
    public function openWorksheet($name, $attributes = null)
    {
        $this->out('<ss:Worksheet ss:Name="' . $name . '">');
        $this->out('<ss:Table>');
        if (isset($attributes) && is_array($attributes)) {
            foreach ($attributes as $attribute) {
                $this->out("<ss:Column ss:Width=\"{$attribute['width']}\"/>");
            }
        }
    }

    public function run()
    {
        try {
            $this->colCount = count($this->columns);
            $this->rowCount = $this->query->count() + 1;
            if ($this->afterExit) $this->sendHeaders();
            $this->openWriter();
            $this->openWorkbook();
            $this->openWorksheet('Экспорт', $this->columns);

            $this->resetRow();
            $this->openRow();
            foreach ($this->columns as $format) {
                $this->appendCellHeader($format['label']);
            }
            $this->closeRow();
            $this->flushRow();

            $this->query->limit(($this->limit) ? : 1000);

            foreach ($this->query->each() as $model) {
                $this->resetRow();
                $this->openRow();
                foreach ($this->columns as $column) {
                    $this->appendCell($column, $model);
                }
                $this->closeRow();
                $this->flushRow();
                unset($model);
            }
            $this->closeWorksheet();
            $this->closeWorkbook();
            $this->closeWriter();
        } catch (Exception $exception) {
            Yii::error($exception->getMessage() . $exception->getTraceAsString(), 'export');
        }
        if ($this->afterExit) exit();
    }

    public function resetRow()
    {
        $this->currentRow = [];
    }

    public function openRow()
    {
        $this->currentRow[] = '<ss:Row>';
    }

    public function closeRow()
    {
        $this->currentRow[] = '</ss:Row>';
    }

    public function flushRow()
    {
        $this->out(implode('', $this->currentRow));
    }

    /**
     * @param $value
     */
    public function appendCellHeader($value)
    {
        $this->currentRow[] = '<ss:Cell ss:StyleID="Header"><ss:Data ss:Type="String">' . htmlspecialchars($value) . '</ss:Data></ss:Cell>';
    }

    /**
     * @param $value
     */
    public function appendCellText($value)
    {
        $this->currentRow[] = '<ss:Cell ss:StyleID="Text"><ss:Data ss:Type="String">' . htmlspecialchars($value) . '</ss:Data></ss:Cell>';
    }

    /**
     * @param $value
     */
    public function appendCellNumber($value)
    {
        $this->currentRow[] = '<ss:Cell ss:StyleID = "Number"><ss:Data ss:Type = "Number">' . floatval($value) . '</ss:Data></ss:Cell>';
    }

    public function appendCellUrl($value, $href)
    {
        if (!empty($href))
            $this->currentRow[] = '<ss:Cell ss:StyleID = "URL" ss:HRef = "' . $href . '"><ss:Data ss:Type = "String">' . htmlspecialchars($value) . '</ss:Data></ss:Cell>';
        else
            $this->appendCellText($value);
    }

    /**
     * @param $value
     */
    public function appendCellDateTime($value)
    {
        if (empty($value))
            $this->appendCellText('');
        else {
            $value = $this->convertMysqlDateTime($value);
            $this->currentRow[] = '<ss:Cell ss:StyleID="DateTime"><ss:Data ss:Type="DateTime">' . $value . '</ss:Data></ss:Cell>';
        }
    }

    /**
     * @param $value
     */
    public function appendCellDate($value)
    {
        if (empty($value))
            $this->appendCellText('');
        else {
            $value = $this->convertMysqlDateTime($value);
            $this->currentRow[] = '<ss:Cell ss:StyleID="Date"><ss:Data ss:Type="DateTime">' . $value . '</ss:Data></ss:Cell>';
        }
    }

    /**
     * @param $value
     */
    public function appendCellTextNoWrap($value)
    {
        $this->currentRow[] = '<ss:Cell ss:StyleID="Text_No_Wrap"><ss:Data ss:Type="String">' . htmlspecialchars($value) . '</ss:Data></ss:Cell>';
    }

    public function closeWorksheet()
    {
        $this->out('</ss:Table>');
        $this->writeWorksheetOptions();
        $this->out('<AutoFilter x:Range="R1C1:R' . $this->rowCount . 'C' . $this->colCount . '" xmlns="urn:schemas-microsoft-com:office:excel"></AutoFilter>');
        $this->out('</ss:Worksheet>');
    }

    public function closeWorkbook()
    {
        $this->out('</ss:Workbook>');
    }

    public function closeWriter()
    {
        //Empty
    }

    public function out($text)
    {
        echo $text . "\n";
    }

    protected function writeBorderStyle($weight = 1)
    {
        $this->out('<ss:Borders>');
        $this->out('<ss:Border ss:Position="Top" ss:LineStyle="Continuous" ss:Color="Automatic" ss:Weight="' . $weight . '"/>');
        $this->out('<ss:Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Color="Automatic" ss:Weight="' . $weight . '"/>');
        $this->out('<ss:Border ss:Position="Left" ss:LineStyle="Continuous" ss:Color="Automatic" ss:Weight="' . $weight . '"/>');
        $this->out('<ss:Border ss:Position="Right" ss:LineStyle="Continuous" ss:Color="Automatic" ss:Weight="' . $weight . '"/>');
        $this->out('</ss:Borders>');
    }

    protected function writeWorksheetOptions()
    {
        $this->out('<WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">');
        $this->out('<Selected></Selected>');
        $this->out('<FreezePanes></FreezePanes>');
        $this->out('<FrozenNoSplit></FrozenNoSplit>');
        $this->out('<SplitHorizontal>1</SplitHorizontal>');
        $this->out('<TopRowBottomPane>1</TopRowBottomPane>');
        $this->out('<ActivePane>2</ActivePane>');
        $this->out('<Panes><Pane><Number>2</Number></Pane></Panes>');
        $this->out('</WorksheetOptions>');
    }

    /**
     * Converts a MySQL type date field to a value that can be used within Excel
     * If the passed value is not valid then the passed string is sent back.
     * @param string $datetime Value must in the format "yyyy-mm-dd hh:ii:ss"
     * or "yyyy-mm-dd"
     * @return string Value in the Excel format "yyyy-mm-ddT00:00:00.000"
     */
    public function convertMysqlDateTime($datetime)
    {
        $datetime = trim($datetime);
        $pattern1 = "/[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}/";
        $pattern2 = "/[0-9]{4}-[0-9]{2}-[0-9]{2}/";
        if (preg_match($pattern1, $datetime, $matches)) {
            $datetime = $matches[0];
            list($date, $time) = explode(' ', $datetime);
            return $date . 'T' . $time . '.000';
        } elseif (preg_match($pattern2, $datetime, $matches)) {
            $date = $matches[0];
            return $date . 'T00:00:00.000';
        } else {
            return htmlspecialchars($datetime);
        }
    }

    protected function appendCell($column, $model)
    {
        $value = call_user_func($column['value'], $model);
        if (isset($column['format'])) {
            switch ($column['format']) {
                case self::TYPE_TEXT:
                    $this->appendCellText($value);
                    break;
                case self::TYPE_HEADER:
                    $this->appendCellHeader($value);
                    break;
                case self::TYPE_NUMBER:
                    $this->appendCellNumber($value);
                    break;
                case self::TYPE_DATE:
                    $this->appendCellDate($value);
                    break;
                case self::TYPE_DATETIME:
                    $this->appendCellDateTime($value);
                    break;
                case self::TYPE_URL:
                    $this->appendCellUrl($value, call_user_func($column['link'], $model));
                    break;
                case self::TYPE_TEXTNOWRAP:
                    $this->appendCellTextNoWrap($value);
                    break;
                default:
                    $this->appendCellText($value);
            }
        } else {
            $this->appendCellText($value);
        }
    }
}