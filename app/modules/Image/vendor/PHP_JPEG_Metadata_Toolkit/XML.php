<?php

/******************************************************************************
*
* Filename:     XML.php
*
* Description:  Provides functions for parsing and constructing XML information
*
* Author:       Evan Hunter
*
* Date:         27/7/2004
*
* Project:      JPEG Metadata
*
* Revision:     1.10
*
* Changes:      1.00 -> 1.10 : Changed read_xml_array_from_text to fix problem that
*                              caused the whitespace (especially newlines) to be
*                              destroyed when converting xml text to an xml array
*
* URL:          http://electronics.ozhiker.com
*
* License:      This file is part of the PHP JPEG Metadata Toolkit.
*
*               The PHP JPEG Metadata Toolkit is free software; you can
*               redistribute it and/or modify it under the terms of the
*               GNU General Public License as published by the Free Software
*               Foundation; either version 2 of the License, or (at your
*               option) any later version.
*
*               The PHP JPEG Metadata Toolkit is distributed in the hope
*               that it will be useful, but WITHOUT ANY WARRANTY; without
*               even the implied warranty of MERCHANTABILITY or FITNESS
*               FOR A PARTICULAR PURPOSE.  See the GNU General Public License
*               for more details.
*
*               You should have received a copy of the GNU General Public
*               License along with the PHP JPEG Metadata Toolkit; if not,
*               write to the Free Software Foundation, Inc., 59 Temple
*               Place, Suite 330, Boston, MA  02111-1307  USA
*
*               If you require a different license for commercial or other
*               purposes, please contact the author: evan@ozhiker.com
*
******************************************************************************/

include_once 'Unicode.php';          // Unicode is required as XML is always Unicode encoded


/******************************************************************************
*
* Function:     read_xml_array_from_text
*
* Description:  Parses a string containing XML, and returns the resulting
*               tree structure array, which contains all the XML information.
*               Note: White space and comments in the XML are ignored
*               Note: All text information contained in the tree structure
*                     is encoded as Unicode UTF-8. Hence text will appear as
*                     normal ASCII except where there is an extended character.
*
* Parameters:   xmltext - a string containing the XML to be parsed
*
* Returns:      output - the tree structure array containing the XML information
*               FALSE - if an error occured
*
******************************************************************************/

function read_xml_array_from_text( $xmltext )
{
        // Check if there actually is any text to parse
        if ( trim( $xmltext ) == "" )
        {
                return FALSE;
        }

        // Create an instance of a xml parser to parse the XML text
        $xml_parser = xml_parser_create( "UTF-8" );


        // Change: Fixed problem that caused the whitespace (especially newlines) to be destroyed when converting xml text to an xml array, as of revision 1.10

        // We would like to remove unneccessary white space, but this will also
        // remove things like newlines (&#xA;) in the XML values, so white space
        // will have to be removed later
        if ( xml_parser_set_option($xml_parser,XML_OPTION_SKIP_WHITE,0) == FALSE )
        {
                // Error setting case folding - destroy the parser and return
                xml_parser_free($xml_parser);
                return FALSE;
        }

        // to use XML code correctly we have to turn case folding
        // (uppercasing) off. XML is case sensitive and upper
        // casing is in reality XML standards violation
        if ( xml_parser_set_option($xml_parser,XML_OPTION_CASE_FOLDING,0) == FALSE )
        {
                // Error setting case folding - destroy the parser and return
                xml_parser_free($xml_parser);
                return FALSE;
        }

        // Parse the XML text into a array structure
        if ( xml_parse_into_struct($xml_parser, $xmltext, $vals, $index) == 0 )
        {
                // Error Parsing XML - destroy the parser and return
                xml_parser_free($xml_parser);
                return FALSE;
        }

        // Destroy the xml parser
        xml_parser_free($xml_parser);


        // Change: Fixed problem that caused the whitespace (especially newlines) to be destroyed when converting xml text to an xml array, as of revision 1.10

        // Since the xml was processed with whitespace enabled, it will have many values which are
        // only whitespace. These need to be removed to make a sensible array.

        $newvals = array( );

        // Cycle through each of the items
        foreach( $vals as $valno => $val )
        {
                // If the item has a whitespace only value, remove it
                if ( ( array_key_exists( 'value', $val ) ) && (trim( $val[ 'value' ] ) == "" ) )
                {
                        unset( $val[ 'value' ] );
                }
                // If the item has a value (which will be non blank now) or is of type other than cdata, add it to the new array
                if ( ( $val[ 'type' ] != 'cdata' ) || ( array_key_exists( 'value', $val ) ) )
                {
                        $newvals[] = $val;
                }

        }

        // The xml_parse_into_struct function returns a flat version
        // of the XML data, where each tag has a level number attached.
        // This is very difficult to work with, so it needs to be
        // converted to a tree structure before being returned
        return xml_get_children($newvals, $i=0);

}

/******************************************************************************
* End of Function:     read_xml_array_from_text
******************************************************************************/





/******************************************************************************
*
* Function:     write_xml_array_to_text
*
* Description:  Takes a tree structure array (in the same format as returned
*               by read_xml_array_from_text, and constructs a string containing
*               the equivalent XML. This function is recursive, and produces
*               XML which has correct indents.
*               Note: All text information contained in the tree structure
*                     can be either 7-bit ASCII or encoded as Unicode UTF-8,
*                     since UTF-8 passes 7-bit ASCII text unchanged.
*
* Parameters:   xmlarray - the tree structure array containing the information to
*                          be converted to XML
*               indentlevel - the indent level of the top level tags (usually zero)
*
* Returns:      output - the string containing the equivalent XML
*               FALSE - if an error occured
*
******************************************************************************/

function write_xml_array_to_text( $xmlarray, $indentlevel )
{
        // Create a string to receive the XML
        $output_xml_text = "";


        // Cycle through each xml element at this level
        foreach ($xmlarray as $xml_elem)
        {

                // Add the indent, then the cleaned tag name to the output
                $output_xml_text .= str_repeat ( " ", $indentlevel ) . "<" . xml_UTF8_clean( $xml_elem['tag'] );

                // Check if there are any attributes for this tag
                if (array_key_exists('attributes',$xml_elem))
                {
                        // There are attributes
                        // Cycle through each attribute for this tag
                        foreach ($xml_elem['attributes'] as  $xml_attr_name => $xml_attr_val)
                        {
                                // Add the cleaned attribute name, and cleaned attribute value to the output
                                $output_xml_text .= " ". xml_UTF8_clean( $xml_attr_name ) ." ='" .  xml_UTF8_clean( $xml_attr_val ) ."'";
                        }
                }

                // Add the 'greater-than' to close this tag to the output
                $output_xml_text .= ">";

                // Check if this element has any text inside it.
                if (array_key_exists('value',$xml_elem) )
                {
                        // There is text for this element - clean it and add it to the output
                        $output_xml_text .=  xml_UTF8_clean( $xml_elem['value'] );
                }

                // Check if there are any lower levels contained by this element
                if (array_key_exists('children',$xml_elem) )
                {
                        // There are sub-elements for this element

                        // Add a newline to the output, so the sub-elements start on a fresh line
                        $output_xml_text .= "\n";

                        // Recursively call this function to output the sub-elements, and add the result to the output
                        $output_xml_text .= write_xml_array_to_text( $xml_elem['children'], $indentlevel + 1 );

                        // Add an indent to the output for the closing tag, since we are on a new line due to the sub-elements
                        $output_xml_text .= str_repeat ( " ", $indentlevel );
                }

                // Add the cleaned closing tag to the output
                $output_xml_text .= "</" .xml_UTF8_clean($xml_elem['tag']) . ">\n";
        }

        // Return the XML text
        return $output_xml_text;
}

/******************************************************************************
* End of Function:     write_xml_array_to_text
******************************************************************************/























/******************************************************************************
*
*         INTERNAL FUNCTIONS
*
******************************************************************************/






/******************************************************************************
*
* Internal Function:     xml_get_children
*
* Description:  Used by the read_xml_array_from_text function.
*               This function recursively converts the values retrieved from
*               the xml_parse_into_struct function into a tree structure array,
*               which is much more useful and easier to use.
*
* Parameters:   input_xml_array - the flat array of XML elements retrieved
*                                 from xml_parse_into_struct
*               $item_num - the number of the element at which the conversion
*                           should start (usually zero when called from another
*                           function, this is used for recursion)
*
* Returns:      children - the tree structure array containing XML elements
*               FALSE - if an error occured
*
******************************************************************************/

function xml_get_children( &$input_xml_array, &$item_num )
{

        // Make an array to receive the output XML tree structure
        $children = array();


        // Cycle through all the elements of the input XML array
        while ( $item_num < count( $input_xml_array ) )
        {
                // Retrieve the current array element
                $v = &$input_xml_array[ $item_num++ ];

                // Check what type of XML array element this is, and process accordingly

                switch ( $v['type'] )
                {
                        case 'cdata':     // This is a non parsed Character Data tag
                        case 'complete':  // This is a pair of XML matching tags possibly with text (but no tags) inside
                                $children[] = xml_get_child( $v );
                                break;

                        case 'open':      // This is a single opening tag
                                // Recursively get the children for this opening tag
                                $children[] = xml_get_child( $v, xml_get_children( $input_xml_array, $item_num ) );
                                break;    // This is a single opening tag

                        case 'close':     // This is a single closing tag
                                break 2;  // leave "while" loop (and the function)
                }
        }

        // Return the results
        return $children;
}

/******************************************************************************
* End of Function:     xml_get_children
******************************************************************************/


/******************************************************************************
*
* Internal Function:     xml_get_child
*
* Description:  Used by the xml_get_children function.
*               Takes an element from an array provided by xml_parse_into_struct
*               and returns an element for a tree structure array
*
* Parameters:   input_xml_item - the item from the array provided by xml_parse_into_struct
*               children - an array of sub-elements to be added to the tree
*                          structure array. Null or missing value indicate no
*                          sub-elements are to be added.
*
* Returns:      child - the element for a tree structure array
*               FALSE - if an error occured
*
******************************************************************************/

function xml_get_child( &$input_xml_item, $children = NULL )
{
        // Create an array to receive the child structure
        $child = array();

        // If the input item has the 'tag' element set, copy it to the child
        if ( isset( $input_xml_item['tag'] ) )
        {
                $child['tag'] = $input_xml_item['tag'] ;
        }

        // If the input item has the 'value' element set, copy it to the child
        if ( isset( $input_xml_item['value'] ) )
        {
                $child['value'] = $input_xml_item['value'] ;
        }

        // If the input item has the 'attributes' element set, copy it to the child
        if ( isset( $input_xml_item['attributes'] ) )
        {
                $child['attributes'] = $input_xml_item['attributes'];
        }

        // If children have been specified, add them to the child
        if ( is_array( $children ) )
        {
                $child['children'] = $children;
        }

        // Return the child structure
        return $child;
}

/******************************************************************************
* End of Function:     xml_get_children
******************************************************************************/












?>