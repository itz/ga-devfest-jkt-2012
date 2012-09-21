<?php
session_start();

echo "<p><a class='logout' href='logout.php'>Logout</a></p>";

include('config.php');
include('function.php');

#xdebug($_SESSION);

$googleauth->setAccessToken($_SESSION['token']);
$analytics = new apiAnalyticsService($googleauth);

if (!isset($_POST['profiles_id'])) {
    /* accessing the management API */
    try {
        $profiles = $analytics->management_profiles->listManagementProfiles("~all", "~all");

        if (is_array($profiles) && isset($profiles['items'])) {
            ?>
            <form action="http://thinkwebdev1.net/devfest/selection.php" method="post" name="show_data">
                <p><strong>Please Select The Profile :</strong></p>
                <p>
                    <select name="profiles_id">
                        <?php
                        foreach ($profiles['items'] as $data) {
                            ?>
                            <option value="<?php echo $data['id']; ?>"><?php echo $data['name']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </p>
                <p>
                    <input type="submit" value="Check Data" />
                </p>
            </div>
            </form>

            <?php
        }
    } catch (apiServiceException $e) {
        print 'There was an API error : ' . $e->getCode() . ' : ' . $e->getMessage();
    } catch (Exception $e) {
        print 'There wan a general error : ' . $e->getMessage();
    }
} else {
    $profile_id = $_POST['profiles_id'];
    /* accesing the management API */

    $data_start_date = date('Y-m-d', strtotime('last month'));
    $data_end_date = date('Y-m-d', strtotime('now'));
    $data_id = 'ga:' . $profile_id;
    $data_metrics = 'ga:visitors,ga:visits,ga:newVisits,ga:avgTimeOnSite,ga:bounces';
    $data_dimension = 'ga:city,ga:country,ga:year,ga:month';
    $data_sort_by = 'ga:year,ga:month,-ga:visitors';

    $optParams = array(
        'dimensions' => $data_dimension,
        'sort' => $data_sort_by,
        'filters' => 'ga:city!=(not set)',
        'max-results' => 5
    );
    try {
        $records = $analytics->data_ga->get($data_id, $data_start_date, $data_end_date, $data_metrics, $optParams);
        if ($records['totalResults'] != '' && $records['totalResults'] != 0) {
            $y = 0;
            foreach ($records['rows'] as $x) {
                if ($x[0] != '') {
                    $data[$y]['dimension'] = $x[2] . $x[3];
                    $data[$y]['city'] = $x[0];
                    $data[$y]['country'] = $x[1];
                    $data[$y]['visitors'] = $x[4];
                    $data[$y]['visits'] = $x[5];
                    $data[$y]['newVisits'] = $x[6];
                    $data[$y]['timeOnSite'] = $x[7];
                    $data[$y]['bounces'] = $x[8];

                    $y++;
                }
            }
        }
        ?>
        <table border="1">
            <tr>
                <td>Tanggal</td>
                <td>Kota</td>
                <td>Negara</td>
                <td>Unique Visitors</td>
                <td>Visits</td>
                <td>New Visits</td>
                <td>Time On Site</td>
                <td>Bounces</td>
            </tr>
            <?php
            foreach ($data as $row) {
                ?>
                <tr>
                    <td><?php echo $row['dimension'] ?></td>
                    <td><?php echo $row['city'] ?></td>
                    <td><?php echo $row['country'] ?></td>
                    <td><?php echo $row['visitors'] ?></td>
                    <td><?php echo $row['visits'] ?></td>
                    <td><?php echo $row['newVisits'] ?></td>
                    <td><?php echo minute($row['timeOnSite']) ?></td>
                    <td><?php echo $row['bounces'] ?></td>
                </tr>
                <?php
            }
            ?>
        </table>


        <script type='text/javascript' src='https://www.google.com/jsapi'></script>
        <script type='text/javascript'>
            google.load('visualization', '1', {'packages': ['geochart']});
            google.setOnLoadCallback(drawMarkersMap);

            function drawMarkersMap() {
                var data = new google.visualization.DataTable();
                data.addColumn('string', 'City');
                data.addColumn('number', 'Visits');
                data.addRows([
        <?php
        foreach ($data as $geo) {
            echo "['" . $geo['city'] . "', " . $geo['visits'] . "],";
        }
        ?>
                ]);

                var options = {
                    backgroundColor: '#F0FFFF',
                    datalessRegionColor: '#FAEBD7',
                    region: 'ID',
                    resolution: 'provinces',
                    displayMode: 'markers',
                    colorAxis: {colors: ['#FF8C00', '#8B4500']}
                };

                var chart_geo = new google.visualization.GeoChart(document.getElementById("geo-geo-geo"));
                chart_geo.draw(data, options);
            };
        </script>
        <div id="geo-geo-geo" style="width: 600px; height: 300px; margin: 10px auto; clear: both;"></div>
        <?php
    } catch (apiServiceException $e) {
        print 'There was an API error : ' . $e->getMessage();
    }
}
