function printBlock(blockId){
	$("#" + blockId).print({
    addGlobalStyles : true,
    stylesheet : null,
	mediaPrint : false,
    rejectWindow : true,
    noPrintSelector : ".no-print",
    iframe : false,
    append : null,
    prepend : null
});	
}