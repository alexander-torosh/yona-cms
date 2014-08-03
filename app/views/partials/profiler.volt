<style>
    #profiler {
        position: fixed;
        bottom: 0;
        width: 100%;
        background: black;
        color: white;
        font-size: 12px;
        font-style: normal;
        font-family: Arial;
    }
    #profiler > .bar {
        cursor: pointer;
        padding: 5px;
        display: block;
        border-top: 1px solid #999999;
    }
    #profiler > .bar > .title {
        float: left;
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
        bottom: 33px;
        max-height: 500px;
        overflow: auto;
        background: black;
        width: 100%;
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
            <li>Total Queries: {{ profiler.getNumberTotalStatements() }}</li>
            <li>Total Elapsed Seconds: {{ profiler.getTotalElapsedSeconds() / 1000 }} ms</li>
        </ul>
    </div>
    <div class="results" style="display: none;">
        {% if profiler.getProfiles() %}
            {% for profile in profiler.getProfiles() %}
            <div class="profile">
                <dl>
                    <dt>SQL Statement:</dt>
                    <dd>{{ profile.getSQLStatement() }}</dd>

                    <dt>Total Elapsed Time:</dt>
                    <dd>{{ profile.getTotalElapsedSeconds() / 1000 }} ms</dd>
                </dl>
            </div>
            {% endfor %}
        {% endif %}
    </div>
</div>

<script>
    function profilerToogle()
    {
        $("#profiler > .results").toggle();
    }
</script>