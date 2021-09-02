<script>
    hogehoge = 'hoge:fuga:auau';
    deprs = hogehoge.split(':'); //[ hoge, fuga, auau ]
    alert( deprs );
    deprs2 = hogehoge.split(':').map( function(value) { return JSON.parse( '{"name":"' + value + '"}' ) } );
    alert( deprs2 );
</script>