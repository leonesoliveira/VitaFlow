<?php

use Adianti\Core\AdiantiCoreTranslator;
use Adianti\Database\TConnection;
use Adianti\Database\TCriteria;
use Adianti\Database\TFilter;
use Adianti\Database\TTransaction;
use Adianti\Widget\Base\TElement;
use Adianti\Widget\Template\THtmlRenderer;

/**
 * Abstract class BChart
 *
 * This class represents a base chart widget that can be used to generate 
 * different types of charts using data from a database.
 *
 * @version    7.4
 * @package    widget
 * @subpackage builder
 * @author     Lucas Tomasi
 */
abstract class BChart extends TElement
{
    private $options;
    private $formatsTooltip;

    protected $type;
    protected $name;
    protected $database;
    protected $model;
    protected $fieldGroup;
    protected $fieldValue;
    protected $fieldColor;
    protected $joins;
    protected $totalChart;
    protected $criteria;
    protected $html;
    protected $title;
    protected $legend;
    protected $percentage;
    protected $height;
    protected $width;
    protected $displayFunction;
    protected $tooltipFunction;
    protected $barDirection;
    protected $colors;
    protected $labelValue;
    protected $rotateLegend;
    protected $legendHeight;
    protected $grid;
    protected $showPanel;
    protected $area;
    protected $customClass;
    protected $transformerValue;
    protected $transformerLegend;
    protected $transformerSubLegend;
    protected $customOptions;
    protected $data;
    protected $loaded;
    protected $zoom;
    protected $areaRounded;
    protected $orderByValue;
    protected $legendsLimitShow;
    protected $barStack;
    protected $showMethods;

    /**
     * BChart constructor.
     *
     * Initializes the chart with the necessary configurations and default settings.
     *
     * @param string      $name        The name of the chart.
     * @param string|null $database    The database name used to fetch data.
     * @param string|null $model       The model class name associated with the chart.
     * @param array       $fieldGroup  The fields used for grouping data in the chart.
     * @param string|null $fieldValue  The field used for calculating totals.
     * @param array       $joins       An array of joins to be used in the query.
     * @param string      $totalChart  The aggregation type (sum, max, min, count, avg).
     * @param TCriteria|null $criteria A criteria object to filter the data.
     *
     * @throws Exception If an invalid parameter is provided.
     */
    public function __construct(String $name, ?String $database = null, ?String $model = null, array $fieldGroup = [], ?String $fieldValue = null, array $joins = [], $totalChart = 'sum', ?TCriteria $criteria = NULL)
    {
        parent::__construct('div');

        $this->html = new THtmlRenderer(__DIR__.'/bchart.html');

        $this->name = $name;
        $this->showMethods = [];
        $this->setDatabase($database);
        $this->setModel($model);
        $this->setFieldGroup($fieldGroup);
        $this->setFieldValue($fieldValue);
        $this->setTotal($totalChart);
        $this->setCriteria($criteria??new TCriteria);
        $this->setJoins($joins);

        $this->showLegend();
        $this->showPercentage(FALSE);
        $this->hidePanel(FALSE);
        $this->setSize('100%', 300);

        $this->setDisplayNumeric();

        $this->grid = false;
        $this->area = false;
        $this->areaRounded = false;
        $this->loaded = false;
        $this->zoom = true;
        $this->orderByValue = false;
        $this->colors = ['#1abc9c', '#2ecc71', '#3498db', '#9b59b6', '#34495e', '#f1c40f', '#e67e22', '#e74c3c', '#95a5a6', '#16a085', '#27ae60', '#2980b9', '#8e44ad', '#2c3e50', '#f39c12', '#d35400', '#c0392b', '#7f8c8d', '#6182f7', '#9084c3', '#52812d', '#7af8ba', '#39953d', '#de6a40', '#1876a9', '#ad4316', '#5c1365', '#8b7e65', '#6ef53b', '#aea561', '#8a8fe8', '#b0ab4c', '#a3d22e', '#8b4515', '#5debbf', '#6dca27', '#9a535e', '#2daacc', '#102ac1', '#40d4e9', '#a7e34d', '#2e7888', '#d33da6', '#84f86d', '#fe0de0', '#5a1c46', '#4a98b1', '#2092c4', '#6f921d', '#7a6ea0', '#805d46', '#4669c1', '#63f587', '#325ca0', '#1a782d', '#9a5c25', '#ec7dd8', '#41acc3', '#3abe6c', '#bd65e2', '#eca34c', '#7c0239', '#b38c7e', '#726fa0', '#974bb6', '#54c3ec', '#6bfaa9', '#a9b472', '#953e18', '#fb6524', '#b4f5c1', '#6eaa5d', '#630b47', '#47fc00', '#93277c', '#81fc80', '#f1db20', '#ad6bc0', '#712269', '#36f290', '#e88da8', '#fbadcd', '#9f4923', '#c0bdb4', '#d592d0', '#9f707e', '#8f2d42', '#a5d6e0', '#92866f', '#43395e', '#69c28b', '#b96749', '#7b2bc2', '#ae70ac', '#b9bf26', '#9eb5c3', '#4149d3', '#b4deec', '#a7c4b2', '#58bab3', '#875db2', '#19c2f1', '#19b670', '#5b6aa4', '#78661f', '#830989', '#19898f', '#c3d27b', '#7f4848', '#ceb5fc', '#1f6200', '#4fb154', '#94debe', '#f3e9a3', '#e74431', '#5f635a', '#dfb39e', '#3576a4'];

        parent::add($this->html);
    }

    /**
     * Generates and processes multiple charts by handling database transactions.
     *
     * @param BChart[] ...$charts A variable-length list of chart instances.
     *
     * @throws Exception If an invalid parameter is provided.
     */
    public static function generate(...$charts)
    {
        $chartsDBs = [];
        
        foreach($charts as $chart)
        {
            if (! $chart->canDisplay())
            {
                continue;
            }

            if (! $chart instanceof BTableChart && ! $chart instanceof BIndicator && ! $chart instanceof BChart)
            {
                throw new Exception(AdiantiCoreTranslator::translate('Invalid parameter (^1) in ^2', 'charts', __METHOD__));
            }

            $db = $chart->getDatabase();

            if ( empty($db) )
            {
                continue;
            }

            if ( empty($chartsDBs[$db]) )
            {
                $chartsDBs[$db] = [];
            }

            $chartsDBs[$db][] = $chart;
        }


        foreach($chartsDBs as $db => $charts)
        {
            TTransaction::open($db);

            foreach($charts as $chart)
            {
                $chart->create();
            }
            
            TTransaction::close();
        }
    }

    /**
     * Formats a function name for usage in JavaScript.
     *
     * @param string $nameFunction The function name.
     *
     * @return string The formatted function reference.
     */
    public static function formatFunction($nameFunction)
    {
        return "::{$nameFunction}::";
    }

    /**
     * Checks if the chart is allowed to be displayed.
     *
     * @return bool True if the chart can be displayed, false otherwise.
     */
    public function canDisplay()
    {
        if ($this->showMethods)
        {
            return in_array($_REQUEST['method']??'', $this->showMethods);
        }

        return true;
    }

    /**
     * Defines which methods are allowed to display the chart.
     *
     * @param array $methods The list of allowed methods.
     */
    public function setShowMethods($methods = [])
    {
        $this->showMethods = $methods;
    }

    /**
     * Retrieves the tooltip formatting function.
     *
     * @return array The tooltip function format.
     */
    private function getTootipFunction()
    {
        if (! $this->tooltipFunction )
        {
            return ["format" => ["value" => "::defaultFormatFunction::"]];
        }

        return ["contents" => "::{$this->tooltipFunction}::"];
    }

    /**
     * Retrieves the display function for formatting values.
     *
     * @return string The function name used for formatting values.
     */
    private function getDisplayFunction()
    {
        if (! $this->displayFunction )
        {
            $this->displayFunction = 'defaultFormatFunction';
        }

        return $this->displayFunction;
    }

    /**
     * Sets the type of the chart.
     *
     * @param string $type The type of chart (pie, line, bar, donut).
     *
     * @throws Exception If an invalid type is provided.
     */
    protected function setType($type)
    {
        if (! in_array($type, ['pie', 'line', 'bar', 'donut']))
        {
            throw new Exception(AdiantiCoreTranslator::translate('Invalid parameter (^1) in ^2', $type, __METHOD__));
        }

        $this->type = $type;
    }

    /**
     * Enables ordering of the chart data based on values.
     *
     * @param string $order The order direction ('asc' or 'desc').
     */
    public function enableOrderByValue($order = 'desc')
    {
        $this->orderByValue = $order;
    }

    /**
     * Retrieves the name of the chart.
     *
     * @return string The chart name.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name of the chart.
     *
     * @param string $name The chart name.
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Sets the display limit for legends.
     *
     * @param int $x The maximum number of legends on the x-axis.
     * @param int $y The maximum number of legends on the y-axis.
     */
    public function setLegendsLimitShow($x, $y)
    {
        $this->legendsLimitShow = [$x, $y];
    }

    /**
     * Sets a transformation function for values.
     *
     * @param callable $transformer The callable function to transform values.
     */
    public function setTransformerValue(callable $transformer)
    {
        $this->transformerValue = $transformer;
    }

    /**
     * Sets a transformation function for legends.
     *
     * @param callable $transformer The callable function to transform legends.
     */
    public function setTransformerLegend(callable $transformer)
    {
        $this->transformerLegend = $transformer;
    }

    /**
     * Sets a custom CSS class for the chart.
     *
     * @param string $class The CSS class name.
     */
    public function setCustomClass($class)
    {
        $this->customClass = $class;
    }

    /**
     * Retrieves the database name used by the chart.
     *
     * @return string|null The database name or null if not set.
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * Sets the database name for fetching data.
     *
     * @param string $database The database name.
     */
    public function setDatabase($database)
    {
        $this->database = $database;
    }

    /**
     * Sets the model class for the chart.
     *
     * @param string $model The model class name.
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * Sets the fields used for grouping data in the chart.
     *
     * @param array $fieldGroup The array of field names.
     *
     * @throws Exception If the parameter is invalid.
     */
    public function setFieldGroup($fieldGroup)
    {
        if (! is_array($fieldGroup) || count($fieldGroup) > 2)
        {
            throw new Exception(AdiantiCoreTranslator::translate('Invalid parameter (^1) in ^2', 'fieldGroup', __METHOD__));
        }
        $this->fieldGroup = $fieldGroup;
    }

    /**
     * Sets the field used for calculating totals.
     *
     * @param string $fieldValue The field name.
     */
    public function setFieldValue($fieldValue)
    {
        $this->fieldValue = $fieldValue;
    }

    /**
     * Sets the field used for defining chart colors.
     *
     * @param string $fieldColor The field name.
     */
    public function setFieldColor($fieldColor)
    {
        $this->fieldColor = $fieldColor;
    }

    /**
     * Sets database joins for the query.
     *
     * @param array $joins The array of join conditions.
     */
    public function setJoins($joins)
    {
        $this->joins = $joins;
    }

    /**
     * Sets the total calculation method.
     *
     * @param string $totalChart The aggregation type (sum, max, min, count, avg).
     *
     * @throws Exception If an invalid type is provided.
     */
    public function setTotal($totalChart)
    {
        if (! in_array($totalChart, ['sum', 'max', 'min', 'count', 'avg']))
        {
            throw new Exception(AdiantiCoreTranslator::translate('Invalid parameter (^1) in ^2', $totalChart, __METHOD__));
        }

        $this->totalChart = $totalChart;
    }

    /**
     * Sets the filtering criteria for the chart data.
     *
     * @param TCriteria $criteria The filtering criteria.
     */
    public function setCriteria(TCriteria $criteria)
    {
        $this->criteria = $criteria;
    }

    /**
     * Sets the title of the chart.
     *
     * @param string $title The chart title.
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Enables or disables percentage display.
     *
     * @param bool $percentage Whether to show percentages.
     */
    public function showPercentage($percentage = true)
    {
        $this->percentage = $percentage;
    }

    /**
     * Hides or shows the chart panel.
     *
     * @param bool $hide Whether to hide the panel.
     */
    public function hidePanel($hide = true)
    {
        $this->showPanel = ! $hide;
    }

    /**
     * Enables or disables the legend display.
     *
     * @param bool $legend Whether to show the legend.
     */
    public function showLegend($legend = true)
    {
        $this->legend = $legend;
    }

    /**
     * Rotates the legend and sets its height.
     *
     * @param int $rotate The rotation angle.
     * @param int $height The height of the legend.
     */
    public function setRotateLegend($rotate, $height = 100)
    {
        $this->rotateLegend = $rotate;
        $this->legendHeight = $height;
    }

    /**
     * Retrieves the chart size.
     *
     * @return null
     */
    public function getSize()
    {
        return null;
    }

    /**
     * Sets the width and height of the chart.
     *
     * @param string|int $width  The width of the chart.
     * @param string|int $height The height of the chart.
     */
    public function setSize($width, $height)
    {
        $height = (strstr($height, '%') !== FALSE) ? $height : "{$height}px";
        $width  = (strstr($width, '%') !== FALSE) ? $width : "{$width}px";

        $this->width = $width;
        $this->height = $height;
    }

    /**
     * Sets the tooltip formatting function.
     *
     * @see https://c3js.org/reference.html#tooltip-contents
     *
     * @param string $function The JavaScript function name.
     */
    public function setTootipFunction($function)
    {
        $this->tooltipFunction = $function;
    }

    /**
     * Sets the display function for formatting values.
     *
     * @see https://c3js.org/reference.html#data-labels-format
     *
     * @param string $function The JavaScript function name.
     */
    public function setDisplayFunction($function)
    {
        $this->displayFunction = $function;
    }

    /**
     * Sets the numeric display format.
     *
     * @param int    $precision         Number of decimal places.
     * @param string $decimalSeparator  The decimal separator.
     * @param string $thousandSeparator The thousands separator.
     * @param string $prefix            A prefix for the values.
     * @param string $sufix             A suffix for the values.
     */
    public function setDisplayNumeric($precision = 2,  $decimalSeparator = ',',  $thousandSeparator = '.',  $prefix = '',  $sufix = '')
    {
        $this->precision = $precision;
        $this->decimalSeparator = $decimalSeparator;
        $this->thousandSeparator = $thousandSeparator;
        $this->prefix = $prefix;
        $this->sufix = $sufix;
    }

    /**
     * Loads the chart data from the database based on the defined criteria.
     *
     * This method constructs the SQL query, applies joins and filters, 
     * executes the query, and retrieves the dataset required for rendering the chart.
     *
     * @throws Exception If required parameters (`database`, `model`, `fieldGroup`, or `fieldValue`) are not set.
     */
    private function loadData()
    {
        $items = [];

        if (empty($this->database))
        {
            throw new Exception(AdiantiCoreTranslator::translate('The parameter (^1) of ^2 is required', 'database', __CLASS__));
        }

        if (empty($this->model))
        {
            throw new Exception(AdiantiCoreTranslator::translate('The parameter (^1) of ^2 is required', 'model', __CLASS__));
        }

        if (empty($this->fieldGroup))
        {
            throw new Exception(AdiantiCoreTranslator::translate('The parameter (^1) of ^2 is required', 'fieldGroup', __CLASS__));
        }

        if (empty($this->fieldValue))
        {
            throw new Exception(AdiantiCoreTranslator::translate('The parameter (^1) of ^2 is required', 'fieldValue', __CLASS__));
        }

        $cur_conn = serialize(TTransaction::getDatabaseInfo());
        $new_conn = serialize(TConnection::getDatabaseInfo($this->database));

        $open_transaction = ($cur_conn !== $new_conn);

        // open transaction case not opened
        if ($open_transaction)
        {
            TTransaction::open($this->database);
        }

        $conn = TTransaction::get();

        $entity = (new $this->model)->getEntity();
        $entities = array_keys($this->joins??[]);
        if(!in_array($entity, $entities))
        {
            $entities[] = $entity;
        }

        $entities = implode(', ', $entities);

        if ($this->joins)
        {
            foreach ($this->joins as $join)
            {
                $key = $join[0];

                // Not find dot, insert table name before
                if (strpos($key, '.') === FALSE)
                {
                    $key = "{$entity}.{$key}";
                }

                if(count($join) > 2)
                {
                    $operator = $join[1];
                    $value    = $join[2];
                }
                else
                {
                    $operator = '=';
                    $value    = $join[1];
                }

                // Not find dot, insert table name before
                if (strpos($value, '.') === FALSE)
                {
                    $value = "{$entity}.{$value}";
                }

                $this->criteria->add(new TFilter($key, $operator, "NOESC: {$value}"));
            }
        }

        $sql = new BChartSqlSelect();
        $groups = [];

        if ($this->fieldColor)
        {
            $sql->addColumn("{$this->fieldColor} as color");
            $groups[] = "color";
        }

        foreach($this->fieldGroup AS $key => $fieldGroup)
        {
            // Not find dot, insert table name before
            if (strpos($fieldGroup, '.') === FALSE && strpos($fieldGroup, ':') === FALSE && strpos($fieldGroup, '(') === FALSE)
            {
                $fieldGroupColumn = "{$entity}.{$fieldGroup} as {$fieldGroup}";
                $fieldGroup = "{$entity}.{$fieldGroup}";
            }
            else 
            {
                $column = explode('.', $fieldGroup);
                $fieldGroupColumn = "{$fieldGroup} as fieldGroup{$key}";
            }

            $sql->addColumn($fieldGroupColumn);
            $groups[] = ($fieldGroup);
        }

        // Not find dot, insert table name before
        if (strpos($this->fieldValue, '.') === FALSE && strpos($this->fieldValue, ':') === FALSE && strpos($this->fieldValue, '(') === FALSE)
        {
            $this->fieldValue = "{$entity}.{$this->fieldValue}";
        }

        $orders = $groups;

        if ($this->orderByValue)
        {
            $conn = TTransaction::get();
            $driver = $conn->getAttribute(PDO::ATTR_DRIVER_NAME);
            
            $totalOrder =  'total ';

            if (in_array($driver, array('mssql', 'dblib', 'sqlsrv')))
            {
                $totalOrder = "{$this->totalChart}({$this->fieldValue}) ";
            }

            array_unshift($orders, $totalOrder . $this->orderByValue );
        }
        
        if ($this->fieldColor)
        {
            unset($orders[0]);
        }

        $group = implode(', ', $groups);
        $order = implode(', ', $orders);

        $this->criteria->setProperty('group', $group);
        $this->criteria->setProperty('order', $order);
        
        $sql->addColumn("{$this->totalChart}({$this->fieldValue}) as total");
        $sql->setEntity($entities);
        $sql->setCriteria($this->criteria);

        $stmt = $conn->prepare($sql->getInstruction(TRUE), array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $result = $stmt->execute($this->criteria->getPreparedVars());

        if($result)
        {
            $items = $stmt->fetchAll(PDO::FETCH_NUM);

            if ($this->fieldColor)
            {
                if (count($this->fieldGroup) > 1)
                {
                    $this->colors = array_column($items, 0, 2);
                }
                else if ($this instanceof BBarChart)
                {
                    $this->colors = array_column($items, 0);
                }
                else
                {
                    $this->colors = array_column($items, 0, 1);
                }

                $items = array_map(function($item) {
                    unset($item[0]);
                    return array_values($item);
                }, $items);
            }
        }

        // close connection
        if ($open_transaction)
        {
            TTransaction::close();
        }

        $this->data = $items;
    }

    /**
     * Formats the tooltip content for the chart.
     *
     * This method defines how tooltips should display values, applying any defined transformations.
     * It generates JavaScript callback functions for handling tooltip formatting dynamically.
     */
    private function formatTooltip()
    {
        $simpleCharts = in_array($this->type, ['pie', 'donut']);

        if (! $this->transformerValue)
        {
            if ($simpleCharts)
            {
                $this->options['tooltip'] = $this->getTootipFunction();
            }
        }

        $formats = [];
        $formatsTooltip = new stdClass;

        foreach ( $this->options['data']['columns'] as $column)
        {
            $newColumns = [];

            foreach($column as $key => $value)
            {
                if($key == 0)
                {
                    continue;
                }

                $newColumns[] = $this->transformerValue ? call_user_func($this->transformerValue, $value, $column, $this->options['data']['columns']) : $value;
            }

            $formatsTooltip->{$column[0]} = $newColumns;

            $values = json_encode($newColumns);

            $key = $simpleCharts ? '0' : 'i';

            $formats[$column[0]] = "::(v, id, i, j, k) => { const values = eval('{$values}'); return values[{$key}]; }::";
        }

        $this->formatsTooltip = json_encode($formatsTooltip);

        $this->options['data']['labels'] = ['format' => $formats];

        $return = "tooltipFormats[i][x]";

        if ($simpleCharts)
        {
            $return = "tooltipFormats[i][0]";

            if ( ! $this->percentage)
            {
                $this->options[$this->type]['label']['format'] =  "::(v, id, i, j, k) => {$return}::";;
            }
        }

        $this->options['tooltip']['format'] = ['value' => "::(v,r,i,x,z) => {$return}::"];
    }

    /**
     * Adjusts the configuration for bar charts.
     *
     * This method modifies the chart options to properly format the bar chart,
     * ensuring correct grouping, axis labels, and stacked bar behavior if enabled.
     */
    private function makeOptionsBar()
    {
        $data = $this->options['data']['columns'];

        $this->options[$this->type]['space'] = .1;

        $countGroups = count($this->fieldGroup);

        $labelsData = array_unique(array_column($data, 0));

        $labels = [];
        $labelsFormatted = [];

        foreach($labelsData as $label)
        {
            foreach($labelsData as $label)
            {
                $labels[] = $label;

                if ($this->transformerLegend)
                {
                    $labelsFormatted[] = call_user_func($this->transformerLegend, $label, $labelsData, $data);
                }
                else
                {
                    $labelsFormatted[] = $label;
                }
            }
        }

        $this->options['axis']['rotated'] = ($this->barDirection == 'horizontal');

        // Case more one column group adjust values
        if($countGroups > 1)
        {
            $values = [];

            $registers = (count(array_unique((array_column($data,0)))));

            $line = array_fill(1, $registers, 0);

            foreach($data as $item)
            {
                $key = $item[$countGroups-1];

                if ($this->transformerSubLegend)
                {
                    $key = call_user_func($this->transformerSubLegend, $key, $item, $data);
                }

                if (empty($values[$key]))
                {
                    $values[$key] = array_merge([$key], $line);
                }

                $posi = array_search($item[0], $labels);

                $values[$key][$posi + 1] = $item[$countGroups];
            }

            sort($values);
            
            $data = $values;

            $this->options['axis']['x'] = ['type' => 'category','categories' => $labelsFormatted, 'tick' => ['centered' => true]];

            if ($this->barStack)
            {
                $this->options['data']['groups'] = [array_column($values, 0)];
            }
        }
        else
        {
            $values = array_column($data, 1);

            $valueInit = $this->labelValue??$this->fieldGroup[0];

            $data = [array_merge([$valueInit], $values)];

            $this->options['legend']['show'] = FALSE;
            $this->options['axis']['x'] = ['type' => 'category', 'categories' => $labelsFormatted, 'tick' => ['centered' => true]];
            $this->options['data']['color'] = '::changeColor::';
        }

        $this->options['data']['columns'] = $data;

        $this->formatTooltip();
    }

    /**
     * Adjusts the configuration for line charts.
     *
     * This method prepares the dataset, applies transformations if necessary, 
     * and structures the chart options for correct line chart rendering.
     */
    private function makeOptionsline()
    {
        $data = $this->options['data']['columns'];

        $countGroups = count($this->fieldGroup);

        $labelsData = array_unique(array_column($data, 0));

        $labels = [];
        $labelsFormatted = [];

        foreach($labelsData as $label)
        {
            $labels[] = $label;

            if ($this->transformerLegend)
            {
                $labelsFormatted[] = call_user_func($this->transformerLegend, $label, $labelsData, $data);
            }
            else
            {
                $labelsFormatted[] = $label;
            }
        }

        $this->options['axis']['rotated'] = ($this->barDirection == 'horizontal');

        // Case more one column group adjust values
        if($countGroups > 1)
        {
            $values = [];

            $line = array_fill(1, count($labels), 0);

            foreach($data as $item)
            {
                $key = $item[$countGroups-1];

                if ($this->transformerSubLegend)
                {
                    $key = call_user_func($this->transformerSubLegend, $key, $item, $data);
                }

                if (empty($values[$key]))
                {
                    $values[$key] = array_merge( [$key], $line);
                }

                $posi = array_search($item[0], $labels);

                $values[$key][$posi + 1] = $item[$countGroups];
            }

            sort($values);

            $data = $values;

            $this->options['axis']['x'] = ['type' => 'category','categories' => $labelsFormatted];
        }
        else
        {
            $values = array_column($data, 1);

            $valueInit = $this->labelValue??$this->fieldGroup[0];

            $data = [array_merge([$valueInit], $values)];

            $this->options['legend']['show'] = FALSE;
            $this->options['axis']['x'] = ['type' => 'category', 'categories' => $labelsFormatted];
        }

        $this->options['data']['columns'] = $data;

        $this->formatTooltip();
    }

    /**
     * Adjusts the configuration for pie and donut charts.
     *
     * This method modifies the legend and value transformations, ensuring 
     * that data labels and tooltips are formatted correctly.
     */
    public function makeOptionsPie()
    {
        $data = $this->options['data']['columns'];

        if ($this->transformerLegend)
        {
            $dataFormatted = [];

            foreach ($data as $key => $item)
            {
                $dataFormatted[$key] = $item;
                $dataFormatted[$key][0] = call_user_func($this->transformerLegend, $item[0], $item, $data);
            }

            $this->options['data']['columns'] = $dataFormatted;
        }

        $this->formatTooltip();
    }

    /**
     * Generates the final configuration options for the chart.
     *
     * This method determines the chart type, applies formatting settings, 
     * enables zoom, sets colors, and ensures proper rendering configurations 
     * based on the chart type and user-defined settings.
     */
    public function makeOptions()
    {
        $displayFunction = $this->getDisplayFunction();

        $type = ($this->area) ? ('area' . ($this->areaRounded? '-spline' : '') ) : $this->type;

        $this->options = [
            $this->type => [
                'label' =>  ['format' => $this->percentage ? null : "::{$displayFunction}::"],
            ],
            'size' => [
                'height' => str_replace('px', '', $this->height)
            ],
            'data' => [
                'columns' => $this->data,
                'type' => $type
            ],
            'bindto' => "#{$this->name}-container",
            'legend' => ['show' =>  $this->legend],
            'zoom' => ["enabled" => $this->zoom],
            'axis' => []
        ];

        if ($this->title AND ! $this->showPanel)
        {
            $this->options['title'] = ['text' => $this->title];
        }

        if ($this->colors)
        {
            if ($this->fieldColor)
            {
                $this->options['data']['colors'] = $this->colors;
                $this->options['data']['color'] = "::(color, d) => colors[d.id] ?? color::";
            }
            else
            {
                $this->options['color'] = ['pattern' => $this->colors];
            }
        }

        if ($this->type == 'bar')
        {
            $this->makeOptionsBar();
        }
        else if ($this->type == 'line')
        {
            $this->makeOptionsLine();
        }
        else
        {
            $this->makeOptionsPie();
        }

        if ($this->grid)
        {
            $this->options['grid'] = [
                'x' => [ 'show' => true ],
                'y' => [ 'show' => true ]
            ];
        }

        if($this->rotateLegend)
        {
            $this->options['axis']['x']['tick'] = [
                'rotate' => $this->rotateLegend,
            ];

            $this->options['axis']['x']['height'] = $this->legendHeight;
        }

        if ($this->legendsLimitShow)
        {
            if ( empty($this->options['axis']['x']['tick']) )
            {
                $this->options['axis']['x']['tick'] = [];
            }

            if ( empty($this->options['axis']['y']['tick']) )
            {
                $this->options['axis']['y']['tick'] = [];
            }

            $this->options['axis']['x']['tick']['culling'] = ['max' => $this->legendsLimitShow[0]];
            $this->options['axis']['y']['tick']['count'] = $this->legendsLimitShow[1];
        }
    }

    /**
     * Sets the colors for the chart.
     *
     * @param array $colors An array of color strings.
     */
    public function setColors(array $colors)
    {
        $this->colors = $colors;
    }

    /**
     * Disables zooming on the chart.
     */
    public function disableZoom()
    {
        $this->zoom = FALSE;
    }

    /**
     * Merges user-defined custom options with the default chart settings.
     *
     * @param array $options An associative array containing custom chart options.
     */
    public function setCustomOptions(array $options)
    {
        $this->customOptions = $options;
    }

    /**
     * Loads and prepares the chart data before display.
     */
    public function create()
    {
        $this->loaded = true;
        $this->loadData();
    }

    /**
     * Displays the chart.
     *
     * @throws Exception If the chart type is not defined.
     */
    public function show()
    {
        if (empty($this->type))
        {
            throw new Exception(AdiantiCoreTranslator::translate('The parameter (^1) of ^2 is required', 'type', __CLASS__));
        }

        if (! $this->canDisplay())
        {
            return;
        }

        if (! $this->loaded)
        {
            $this->create();
        }

        $this->makeOptions();

        if ($this->customOptions)
        {
            $this->options = array_replace_recursive($this->options, $this->customOptions);
        }

        $options = json_encode($this->options);
        $options = str_replace('"::', "", $options);
        $options = str_replace('::"', "", $options);

        $this->html->enableSection('main');

        if (empty($this->data))
        {
            $this->html->enableSection(
                'no-data',
                [
                    'class' => $this->customClass,
                    'name' => $this->name,
                    'width' => $this->width,
                    'height' => $this->height,
                    'title' => $this->title,
                    'label' => _t('No records found'),
                ]
            );
        }
        else
        {
            $this->html->enableSection(
                'data',
                [
                    'class' => $this->customClass,
                    'name' => $this->name,
                    'options' => $options,
                    'width' => $this->width,
                    'precision' => $this->precision,
                    'decimalSeparator' => $this->decimalSeparator,
                    'thousandSeparator' => $this->thousandSeparator,
                    'prefix' => $this->prefix,
                    'sufix' => $this->sufix,
                    'colors' => json_encode($this->colors??[]),
                    'tooltipFormats' => $this->formatsTooltip??'0'
                ]
            );
    
            $this->html->enableSection( $this->showPanel ? 'panel' : 'nopanel', ['name' => $this->name]);

            if ($this->title && $this->showPanel)
            {
                $this->html->enableSection('header', ['title' => $this->title]);
            }
        }


        parent::show();
    }
}
