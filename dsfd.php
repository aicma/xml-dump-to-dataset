<?php
$xsl = "<xsl:stylesheet version=\"1.0\" xmlns:xsl=\"http://www.w3.org/1999/XSL/Transform\">
    <xsl:template match=\"/\">
    <dataset><xsl:text>&#xa;</xsl:text>
        <xsl:for-each select=\"pma_xml_export/database/table\">
        
        <xsl:variable name=\"e\" select=\"@name\"/>
        <xsl:element name=\"{\$e}\"><xsl:text>&#xa;</xsl:text>
            <xsl:for-each select='column'>
                <xsl:variable name='c' select='@name'/>
                <xsl:element name='{\$c}'>
                    <xsl:value-of select='.' />
                </xsl:element><xsl:text>&#xa;</xsl:text>
            </xsl:for-each>
        </xsl:element><xsl:text>&#xa;</xsl:text>
      <xsl:text>&#xa;</xsl:text>
        </xsl:for-each>
    </dataset>
    </xsl:template>

</xsl:stylesheet>
";


if(empty($argv[1])){
    exit("bitte name des Dumps angeben!\n");
}else{
    $sourceFile = $argv[1];
}
if(file_exists($sourceFile)){
    $xml = simplexml_load_file($sourceFile);

    $import = loadFile($sourceFile, $xsl);
    file_put_contents($sourceFile."dataset.xml", $import);
}else{
    exit("no '".$sourceFile."' found");
}

function loadFile($xml, $xsl)
{
    $xmlDoc = new DOMDocument();
    $xmlDoc->load($xml);

    $xslDoc = simplexml_load_string($xsl);

    $proc = new XSLTProcessor();
    $proc->importStyleSheet($xslDoc);
    return $proc->transformToXML($xmlDoc);
}
