
<div class="container">
    <div class="row">

        <div class="col-lg-12 well-lg" style="margin-top: 5%;padding: 5px;">
            <h4>{lng_stat_for} {url_short}</h4>
            <h5>{url_long}</h5>

            <table class="table">
                <thead class="thead-dark table-bordered ">
                    <tr>
                        <th>{lng_stat_date}</th>
                        <th>{lng_stat_cnt}</th>
                    </tr>
                </thead>
                <tbody>
                    <{BLOCK=ZEILE}>
                        <tr>
                            <td>{datum}</td>
                            <td>{cnt}</td>
                        </tr>
                    <{/BLOCK=ZEILE}>
                </tbody>
            </table>

        </div>
    </div>
</div>

