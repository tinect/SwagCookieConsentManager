{block name="widgets_index_statistic_include"}
    <iframe id="refresh-statistics" width="0" height="0" style="display:none;"></iframe>
    <script type="text/javascript">
        var indexUrl = '{url controller=index}';
        var controllerName = '{$Controller|escape}';
        var blogId = '{$sArticle.id}';
        var articleId = '{$sArticle.articleID}';
        var refreshStatiticsUrl = '{url module=widgets controller=index action=refreshStatistic}';
    </script>
{/block}
