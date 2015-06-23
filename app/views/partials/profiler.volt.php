<style>
    #profiler {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background: black;
        color: white;
        font-size: 12px;
        font-style: normal;
        font-family: Arial;
    }
    #profiler > .bar {
        cursor: pointer;
        height: 20px;
        padding: 5px;
        display: block;
        border-top: 1px solid #999999;
    }
    #profiler > .bar > .title {
        float: left;
        font-weight: bold;
    }
    #profiler > .bar > .info {
        float: left;
        margin-left: 20px;
    }
    #profiler > .bar > .info > li {
        float: left;
        margin-right: 20px;
    }
    #profiler > .results {
        position: absolute;
        bottom: 30px;
        max-height: 500px;
        overflow: auto;
        background: black;
        width: 100%;
        border-bottom: 1px solid #666666;
    }
    #profiler > .results > .profile {
        background: #333333;
        margin: 5px;
    }
    dl {
        margin: 0;
    }
</style>

<div id="profiler">
    <div class="bar clearfix" onclick="profilerToogle();">
        <section class="title">Profiler</section>
        <ul class="info">
            <li>Total SQL queries: <?php echo $this->profiler->getNumberTotalStatements(); ?></li>
            <?php $seconds = round($profiler->getTotalElapsedSeconds(), 5) ?>
            <li>Total SQL elapsed seconds: <?php echo sprintf(($seconds * 1000), '%01.2f'); ?> ms</li>
            <li>Memory usage: <?php echo round(memory_get_peak_usage(true)/1024/1024, 2); ?> Mb</li>
            <?php $time_end = microtime(true); ?>
            <li></li>
        </ul>
    </div>
    <div class="results" style="display: none;">
        <?php if ($this->profiler->getProfiles()) { ?>
            <?php foreach ($this->profiler->getProfiles() as $profile) { ?>
            <div class="profile">
                <dl>
                    <dt>SQL Statement:</dt>
                    <dd><?php echo $profile->getSQLStatement(); ?></dd>

                    <dt>Total Elapsed Time:</dt>
                    <?php $seconds = round($profile->getTotalElapsedSeconds(), 5) ?>
                    <dd><?php echo sprintf(($seconds * 1000), '%01.2f'); ?> ms</dd>
                </dl>
            </div>
            <?php } ?>
        <?php } ?>
    </div>
</div>

<script>
    function profilerToogle()
    {
        $("#profiler > .results").toggle();
    }
</script>