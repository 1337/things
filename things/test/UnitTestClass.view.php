<?php
    function showResults ($unit_test_classes) {
    ?>
        <html>
            <head>
                <style type="text/css">
                    html *, body * {
                        font-family:Verdana, Geneva, sans-serif;
                        font-size: 12px;
                    }
                    #results tr:nth-child(even) {
                        background: #eee;
                    }
                    #results tr:nth-child(odd) {
                        background: #fff;
                    }
                    #results tr td, #results tr th {
                        padding: 5px;
                    }
                    #header {
                        padding: 0 0 30px 0;
                        font-size: 10px;
                    }
                </style>
            </head>
            <body>
                <div id="header">
                    <b>UnitTestClass.view.php</b>
                    <br />
                    Last updated <?php echo (date ('F j, Y', filemtime (__FILE__))); ?>
                    <br />
                    Tests run <?php echo (date ('H:i:s', time ())); ?>
                </div>
                <?php
                    if (!is_array ($unit_test_classes)) {
                        // array type needed for foreach later
                        $unit_test_classes = array ($unit_test_classes);
                    }
                    foreach ($unit_test_classes as $unit_test_class) {
                ?>
                    <h2>Class 
                        <?php
                            echo (get_class ($unit_test_class));
                            echo ("<br />");
                            echo ($_SERVER['SCRIPT_FILENAME']);
                        ?>
                    </h2>
                    <table id="results">
                        <tr>
                            <th>#</th>
                            <th>Test</th>
                            <th>Result</th>
                            <th>Expected</th>
                        </tr>
                        <?php
                            $i = 0;
                            foreach ($unit_test_class->resultstack as $result) {
                                $i++;
                                echo ("
                                <tr>
                                    <td>$i</td>
                                    <td>" . $result['method'] . "</td>
                                    <td>" . 
                                        ($result['success'] ? 
                                            "Passed" : 
                                            "<span style='background-color:red;
                                                          color:white;
                                                          padding:4px;
                                                          border-radius:3px;'>
                                                Failed
                                            </span>"
                                        ) . 
                                    "</td>
                                    <td>" . $result['message'] . "</td>
                                </tr>");
                            }
                        ?>
                    </table>
                <?php
                    }
                ?>
            </body>
        </html>
<?php
    }
?>