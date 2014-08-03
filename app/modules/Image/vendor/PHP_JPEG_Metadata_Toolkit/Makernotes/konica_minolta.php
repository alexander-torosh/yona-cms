<?php

/******************************************************************************
*
* Filename:     konica_minolta.php
*
* Description:  Konica/Minolta Makernote Parser
*               Provides functions to decode an Konica/Minolta EXIF makernote and
*               to interpret the resulting array into html.
*
*               Konica/Minolta Makernote Formats:
*
*               Type 1:
*
*               Field           Size            Description
*               ----------------------------------------------------------------
*               Header          3 Bytes         "MLY"
*               Unknown Data    Variable        Unknown Data
*               ----------------------------------------------------------------
*
*               Type 2:
*
*               Field           Size            Description
*               ----------------------------------------------------------------
*               Header          2 Bytes         "KC"
*               Unknown Data    Variable        Unknown Data
*               ----------------------------------------------------------------
*
*               Type 3:
*
*               Field           Size            Description
*               ----------------------------------------------------------------
*               Header          8 Bytes         "+M+M+M+M"
*               Unknown Data    Variable        Unknown Data
*               ----------------------------------------------------------------
*
*               Type 4:
*
*               Field           Size            Description
*               ----------------------------------------------------------------
*               Header          5 Bytes         "MINOL"
*               Unknown Data    Variable        Unknown Data
*               ----------------------------------------------------------------
*
*               Type 5:   NO HEADER
*
*               Field           Size            Description
*               ----------------------------------------------------------------
*               IFD Data        Variable        Standard IFD with Olympus Tags
*               ----------------------------------------------------------------
*
*
* Author:       Evan Hunter
*
* Date:         30/7/2004
*
* Project:      JPEG Metadata
*
* Revision:     1.00
*
* URL:          http://electronics.ozhiker.com
*
* Copyright:    Copyright Evan Hunter 2004
*               This file may be used freely for non-commercial purposes.For
*               commercial uses please contact the author: evan@ozhiker.com
*
******************************************************************************/

// Konica/Minolta makernote uses Olympus tags - ensure they are included

include_once 'olympus.php';


// Add the parser functions to the list of Makernote parsers . (Interpreting done by Olympus script)

$GLOBALS['Makernote_Function_Array']['Read_Makernote_Tag'][] = "get_Minolta_Makernote";
$GLOBALS['Makernote_Function_Array']['get_Makernote_Text_Value'][] = "get_Minolta_Text_Value";



/******************************************************************************
*
* Function:     get_Minolta_Makernote
*
* Description:  Decodes the Makernote tag and returns the new tag with the decoded
*               information attached. Returns false if this is not a makernote
*               that can be processed with this script
*
* Parameters:   Makernote_Tag - the element of an EXIF array containing the
*                               makernote, as returned from get_EXIF_JPEG
*               EXIF_Array - the entire EXIF array containing the
*                            makernote, as returned from get_EXIF_JPEG, in
*                            case more information is required for decoding
*               filehnd - an open file handle for the file containing the
*                         makernote - does not have to be positioned at the
*                         start of the makernote
*               Make_Field - The contents of the EXIF Make field, to aid
*                            determining whether this script can decode
*                            the makernote
*
*
* Returns:      Makernote_Tag - the Makernote_Tag from the parameters, but
*                               modified to contain the decoded information
*               FALSE - If this script could not decode the makernote, or if
*                       an error occured in decoding
*
******************************************************************************/

function get_Minolta_Makernote( $Makernote_Tag, $EXIF_Array, $filehnd, $Make_Field )
{

        if ( ( stristr( $Make_Field, "Konica" ) === FALSE ) &&
             ( stristr( $Make_Field, "Minolta" ) === FALSE ) )
        {
                // Not a Konica/Minolta Makernote - Cannot decode it
                return False;
        }

        // There are several different headers for a Konica/Minolta Makernote
        // Unfortunately only one type can be decoded (the one without a header)
        // Check which header exists (if any)
        if ( substr( $Makernote_Tag['Data'], 0, 3 ) == "MLY" )
        {
                // MLY Header - Can't Decode this
                return $Makernote_Tag;
        }
        else if ( substr( $Makernote_Tag['Data'], 0, 2 ) == "KC" )
        {
                // KC Header - Can't Decode this
                return $Makernote_Tag;
        }
        if ( substr( $Makernote_Tag['Data'], 0, 8 ) == "+M+M+M+M" )
        {
                // +M+M+M+M Header - Can't Decode this
                return $Makernote_Tag;
        }
        else if ( substr( $Makernote_Tag['Data'], 0, 5 ) == "MINOL" )
        {
                // MINOL Header - Can't Decode this
                return $Makernote_Tag;
        }
        else
        {
                // No Header - Decode the IFD

                // Seek to the start of the IFD
                fseek($filehnd, $Makernote_Tag['Tiff Offset'] + $Makernote_Tag['Offset'] );

                // Read the IFD(s) into an array
                $Makernote_Tag['Decoded Data'] = read_Multiple_IFDs( $filehnd, $Makernote_Tag['Tiff Offset'], $Makernote_Tag['ByteAlign'], "Olympus" );

                // Save some information into the Tag element to aid interpretation
                $Makernote_Tag['Decoded'] = TRUE;
                $Makernote_Tag['Makernote Type'] = "Minolta";
                $Makernote_Tag['Makernote Tags'] = "Olympus";


                // Return the new tag
                return $Makernote_Tag;

        }


        // Shouldn't get here
        return False;
}

/******************************************************************************
* End of Function:     get_Minolta_Makernote
******************************************************************************/







/******************************************************************************
*
* Function:     get_Minolta_Text_Value
*
* Description:  Provides a text value for any tag marked as special for makernotes
*               that this script can decode. Returns false if this is not a makernote
*               that can be processed with this script
*
* Parameters:   Exif_Tag - the element of an the Makernote array containing the
*                          tag in question, as returned from get_Olympus_Makernote
*               Tag_Definitions_Name - The name of the Tag Definitions group
*                                      within the global array IFD_Tag_Definitions
*
*
* Returns:      output - the text value for the tag
*               FALSE - If this script could not decode the makernote, or if
*                       an error occured in decoding
*
******************************************************************************/

function get_Minolta_Text_Value( $Exif_Tag, $Tag_Definitions_Name )
{
        // Check that this Tag uses Olympus type tags - otherwise it cannot be processed here
        if ( $Tag_Definitions_Name !== "Olympus" )
        {
                // Not Olympus Tags - cannot be processed here
                return FALSE;
        }


        // Process the tag acording to it's tag number, to produce a text value

        if ( ( $Exif_Tag['Tag Number'] == 0x0001 ) ||   // Minolta Camera Settings
             ( $Exif_Tag['Tag Number'] == 0x0003 ) )
        {

                // Create the output string
                $output_str = "";

                // Cycle through each camera setting record which are 4 byte Longs

                for ( $i = 1; $i*4 <= strlen( $Exif_Tag['Data'] ); $i++)
                {
                
                        // Exract the current 4 byte Long value (Motorola byte alignment)
                        $value = get_IFD_Data_Type( substr($Exif_Tag['Data'], ($i-1)*4, 4) , 4, "MM" );

                        // Corrupt settings can cause huge values, which automatically get
                        // put into floating point variables instead of integer variables
                        // Hence Check that this is an integer, as problems will occur if it isn't
                        if ( is_integer( $value ) )
                        {
                        
                                // Check if the current setting number is in the Definitions array
                                if ( array_key_exists( $i, ($GLOBALS[ "Minolta_Camera_Setting_Definitions" ]) ) === TRUE )
                                {
                                        // Setting is in definitions array

                                        // Get some of the information from the settings definitions array
                                        $tagname = $GLOBALS[ "Minolta_Camera_Setting_Definitions" ][ $i ][ 'Name' ];
                                        $units = "";
                                        if ( array_key_exists( 'Units', $GLOBALS[ "Minolta_Camera_Setting_Definitions" ][ $i ] ) )
                                        {
                                                $units = $GLOBALS[ "Minolta_Camera_Setting_Definitions" ][ $i ][ 'Units' ];
                                        }
                                        // Check what type of field the setting is, and process accordingly
                                        
                                        if ( $GLOBALS[ "Minolta_Camera_Setting_Definitions" ][ $i ]['Type'] == "Lookup" )
                                        {
                                                // This is a lookup table field

                                                // Check if the value read is in the lookup table
                                                if ( array_key_exists( $value, $GLOBALS[ "Minolta_Camera_Setting_Definitions" ][ $i ] ) )
                                                {
                                                        // Value is in the lookup table - Add it to the text
                                                        $output_str .= $tagname . ": " . $GLOBALS[ "Minolta_Camera_Setting_Definitions" ][ $i ][ $value ] . "\n";
                                                }
                                                else
                                                {
                                                        // Value is Not in the lookup table
                                                        // Add a message if the user has requested to see unknown tags
                                                        if ( $GLOBALS['HIDE_UNKNOWN_TAGS'] == FALSE )
                                                        {
                                                                $output_str .= $tagname . ": Unknown Reserved Value $value\n";
                                                        }

                                                }
                                        }
                                        else if ( $GLOBALS[ "Minolta_Camera_Setting_Definitions" ][ $i ]['Type'] == "Numeric" )
                                        {
                                                // This is a numeric type add it as is to the output, with units
                                                $output_str .= $tagname . ": $value $units\n";
                                        }
                                        else if ( $GLOBALS[ "Minolta_Camera_Setting_Definitions" ][ $i ]['Type'] == "Special" )
                                        {
                                                // This is a special setting, Process it according to the setting number
                                                switch ( $i )
                                                {
                                                        case 9:         // Apex Film Speed Value
                                                                $output_str .= $tagname . ": " . ($value/8-1) . " ( ISO " . ((pow(2,($value/8-1)))*3.125) . " )\n";
                                                                break;
                                                                
                                                        case 10:        // Apex Shutter Speed Time Value
                                                                $output_str .= $tagname . ": " . ($value/8-6);
                                                                if ( $value == 8 )
                                                                {
                                                                        $output_str .=  " ( 30 seconds )\n";
                                                                }
                                                                else
                                                                {
                                                                        $output_str .=  " ( " . ( pow(2, (48-$value)/8 ) ) . " seconds )\n";
                                                                }
                                                                break;
                                                                
                                                        case 11:        // Apex Aperture Value
                                                                $output_str .= $tagname . ": " . ($value/8-1) . " ( F Stop: " . (pow(2,( $value/16-0.5 ))) . " )\n";
                                                                break;
                                                                
                                                        case 14:        // Exposure Compensation
                                                                $output_str .= $tagname . ": " . ($value/3-2) . " $units\n";
                                                                break;
                                                                
                                                        case 17:        // Interval Length
                                                                $output_str .= $tagname . ": " . ($value+1) . " $units\n";
                                                                break;
                                                                
                                                        case 19:        // Focal Length
                                                                $output_str .= $tagname . ": " . ($value/256) . " $units\n";
                                                                break;
                                                                
                                                        case 22:        // Date
                                                                $output_str .= $tagname . ": " . sprintf( "%d/%d/%d",  ($value%256), floor(($value - floor($value/65536)*65536)/256 ), floor($value/65536) ) . " $units\n";
                                                                break;
                                                                
                                                        case 23:        // Time
                                                                $output_str .= $tagname . ": " . sprintf( "%2d:%02d:%02d", floor($value/65536), floor(($value - floor($value/65536)*65536)/256 ), ($value%256) ) . " $units\n";
                                                                break;
                                                                
                                                        case 24:        // Max Aperture at this focal length
                                                                $output_str .= $tagname . ": F" . (pow(2,($value/16-0.5))) ." $units\n";
                                                                break;
                                                                
                                                        case 29:        // White Balance Red
                                                        case 30:        // White Balance Green
                                                        case 31:        // White Balance Blue
                                                                $output_str .= $tagname . ": " . ($value/256) ." $units\n";
                                                                break;
                                                                
                                                        case 32:        // Saturation
                                                        case 33:        // Contrast
                                                                $output_str .= $tagname . ": " . ($value-3) ." $units\n";
                                                                break;
                                                                
                                                        case 36:        // Flash Compensation
                                                                $output_str .= $tagname . ": " . (($value-6)/3) ." $units\n";
                                                                break;
                                                                
                                                        case 42:        // Color Filter
                                                                $output_str .= $tagname . ": " . ($value-3) ." $units\n";
                                                                break;
                                                                
                                                        case 45:        // Apex Brightness Value
                                                                $output_str .= $tagname . ": " . ($value/8-6) ." $units\n";
                                                                break;
                                                                
                                                        default:        // Unknown Special Setting
                                                                // If user has requested to see the unknown tags, then add the setting to the output
                                                                if ( $GLOBALS['HIDE_UNKNOWN_TAGS'] == FALSE )
                                                                {
                                                                        $output_str .= "Unknown Special Tag: $tagname, Value: $value $units\n";
                                                                }
                                                                break;
                                                }
                                        }
                                        else
                                        {
                                                // Unknown Setting Type
                                                // If user has requested to see the unknown tags, then add the setting to the output
                                                if ( $GLOBALS['HIDE_UNKNOWN_TAGS'] == FALSE )
                                                {
                                                        $output_str .= "Unknown Tag Type Tag $i, Value: " . $value . "\n";
                                                }
                                        }


                                }
                                else
                                {
                                        // Unknown Setting
                                        // If user has requested to see the unknown tags, then add the setting to the output
                                        if ( $GLOBALS['HIDE_UNKNOWN_TAGS'] == FALSE )
                                        {
                                                $output_str .= "Unknown Minolta Camera Setting Tag $i, Value: " . $value . "\n";
                                        }
                                }
                        }

                }
                
                // Return the text string
                return $output_str;
        }
        else if ( ( $Exif_Tag['Tag Number'] == 0x0088 ) ||
                  ( $Exif_Tag['Tag Number'] == 0x0081 ) )
        {
                // Konica/Minolta Thumbnail
                return "Thumbnail";
        }
        else
        {
                return FALSE;
        }

}

/******************************************************************************
* End of Function:     get_Minolta_Text_Value
******************************************************************************/







/******************************************************************************
* Global Variable:      Minolta_Camera_Setting_Definitions
*
* Contents:     This global variable provides definitions for the fields
*               contained in the Konica/Minolta Camera Settings Makernote tag,
*               indexed by their setting number.
*
******************************************************************************/

$GLOBALS[ "Minolta_Camera_Setting_Definitions" ] = array(

2 => array ( 'Name' => "Exposure Mode",
                'Type' => "Lookup",
                 0 => "P",
                 1 => "A",
                 2 => "S",
                 3 => "M" ),

3 => array (   'Name' => "Flash Mode",
                'Type' => "Lookup",
                0 => "Normal",
                1 => "Red-eye reduction",
                2 => "Rear flash sync",
                3 => "Wireless" ),

4 => array (   'Name' => "White Balance",
                'Type' => "Lookup",
                0 => "Auto",
                1 => "Daylight",
                2 => "Cloudy",
                3 => "Tungsten",
                5 => "Custom",
                7 => "Fluorescent",
                8 => "Fluorescent 2",
                11 => "Custom 2",
                12 => "Custom 3" ),

5 => array (   'Name' => "Image Size",
                'Type' => "Lookup",
                0 => "2560 x 1920 (2048x1536 - DiMAGE 5 only)",
                1 => "1600 x 1200",
                2 => "1280 x 960",
                3 => "640 x 480" ),


6 => array (   'Name' => "Image Quality",
                'Type' => "Lookup",
                0 => "Raw",
                1 => "Super Fine",
                2 => "Fine",
                3 => "Standard",
                4 => "Economy",
                5 => "Extra Fine" ),

7 => array (   'Name' => "Shooting Mode",
                'Type' => "Lookup",
                0 => "Single",
                1 => "Continuous",
                2 => "Self-timer",
                4 => "Bracketing",
                5 => "Interval",
                6 => "UHS Continuous",
                7 => "HS Continuous" ),


8 => array (   'Name' => "Metering Mode",
                'Type' => "Lookup",
                0 => "Multi-Segment",
                1 => "Centre Weighted",
                2 => "Spot" ),


9 => array (   'Name' => "Apex Film Speed Value",
                'Type' => "Special" ),

// 09 FilmSpeed ,  APEX Film Speed Value ,  Speed value = x/8-1 , ISO= (2^(x/8-1))*3.125


10 => array (   'Name' => "Apex Shutter Speed Time Value",
                'Type' => "Special",
                'Units' => "Seconds?" ),

//  APEX Time Value ,   Time value = x/8-6 ,  ShutterSpeed = 2^( (48-x)/8 ), ! Due to rounding error x=8 should be displayed as 30 sec.

11 => array (   'Name' => "Apex Aperture Value",
                'Type' => "Special" ),

// APEX Aperture Value   ApertureValue = x/8-1  , Aperture = 2^( x/16-0.5 )


12 => array (   'Name' => "Macro Mode",
                'Type' => "Lookup",
                0 => "Off",
                1 => "On" ),

13 => array (   'Name' => "Digital Zoom",
                'Type' => "Lookup",
                0 => "Off",
                1 => "Electronic magnification was used",
                2 => "Digital zoom 2x" ),


14 => array (   'Name' => "Exposure Compensation",
                'Type' => "Special",
                'Units' => "EV" ),

// EV = x/3 -2  Exposure compensation in EV


15 => array (   'Name' => "Bracket Step",
                'Type' => "Lookup",
                0 => "1/3 EV",
                1 => "2/3 EV",
                2 => "1 EV" ),


17 => array (   'Name' => "Interval Length",
                'Type' => "Special",
                'Units' => "Min" ),

// interval is x+1 min (used with interval mode)


18 => array (   'Name' => "Interval Number",
                'Type' => "Numeric",
                'Units' => "frames" ),

19 => array (   'Name' => "Focal Length",
                'Type' => "Special",
                'Units' => "mm" ),

//   x / 256 is real focal length in mm  ,  x / 256 * 3.9333 is 35-mm equivalent


20 => array (   'Name' => "Focus Distance",
                'Type' => "Numeric",
                'Units' => "mm  ( 0 = Infinity)" ),


21 => array (   'Name' => "Flash Fired",
                'Type' => "Lookup",
                0 => "No",
                1 => "Yes" ),

22 => array (   'Name' => "Date",
                'Type' => "Special"  ),

// yyyymmdd ,  year = x/65536 , month = x/256-x/65536*256 , day = x%256

23 => array (   'Name' => "Time",
                'Type' => "Special"  ),

// hhhhmmss , hour = x/65536 , minute = x/256-x/65536*256 , second = x%256


24 => array (   'Name' => "Max Aperture at this focal length",
                'Type' => "Special"  ),

// Fno = 2^(x/16-0.5)


27 => array (   'Name' => "File Number Memory",
                'Type' => "Lookup",
                0 => "Off",
                1 => "On" ),

28 => array (   'Name' => "Last File Number",
                'Type' => "Numeric",
                'Units' => "  ( 0 = File Number Memory is Off)" ),


29 => array (   'Name' => "White Balance Red",
                'Type' => "Special"  ),

// x/256 - red white balance coefficient used for this picture


30 => array (   'Name' => "White Balance Green",
                'Type' => "Special"  ),

// x/256 - green white balance coefficient used for this picture

31 => array (   'Name' => "White Balance Blue",
                'Type' => "Special"  ),

// x/256 - blue white balance coefficient used for this picture


32 => array (   'Name' => "Saturation",
                'Type' => "Special"  ),

//  x-3 = saturation


33 => array (   'Name' => "Contrast",
                'Type' => "Special"  ),

// x-3 - contrast


34 => array (   'Name' => "Sharpness",
                'Type' => "Lookup",
                0 => "Hard",
                1 => "Normal",
                2 => "Soft" ),


35 => array (   'Name' => "Subject Program",
                'Type' => "Lookup",
                0 => "none",
                1 => "portrait",
                2 => "text",
                3 => "night portrait",
                4 => "sunset",
                5 => "sports action" ),


36 => array (   'Name' => "Flash Compensation",
                'Type' => "Special",
                'Units' => "EV"  ),

//  (x-6)/3 = flash compensation in EV


37 => array (   'Name' => "ISO Setting",
                'Type' => "Lookup",
                0 => "100",
                1 => "200",
                2 => "400",
                3 => "800",
                4 => "auto",
                5 => "64" ),


38 => array (   'Name' => "Camera Model",
                'Type' => "Lookup",
                0 => "DiMAGE 7",
                1 => "DiMAGE 5",
                2 => "DiMAGE S304",
                3 => "DiMAGE S404",
                4 => "DiMAGE 7i",
                5 => "DiMAGE 7Hi",
                6 => "DiMAGE A1",
                7 => "DiMAGE S414" ),


39 => array (   'Name' => "Interval Mode",
                'Type' => "Lookup",
                0 => "Still Image",
                1 => "Time-lapse Movie" ),


40 => array (   'Name' => "Folder Name",
                'Type' => "Lookup",
                0 => "Standard Form",
                1 => "Data Form" ),


41 => array (   'Name' => "Color Mode",
                'Type' => "Lookup",
                0 => "Natural Color",
                1 => "Black & White",
                2 => "Vivid Color",
                3 => "Solarization",
                4 => "Adobe RGB" ),


42 => array (   'Name' => "Color Filter",
                'Type' => "Special" ),

// x-3 = color filter


43 => array (   'Name' => "Black & White Filter",
                'Type' => "Numeric" ),



44 => array (   'Name' => "Internal Flash",
                'Type' => "Lookup",
                0 => "Not Fired",
                1 => "Fired" ),



45 => array (   'Name' => "Apex Brightness Value",
                'Type' => "Special" ),

// Brightness Value = x/8-6




46 => array (   'Name' => "Spot Focus Point X Coordinate",
                'Type' => "Numeric" ),



47 => array (   'Name' => "Spot Focus Point Y Coordinate",
                'Type' => "Numeric" ),



48 => array (   'Name' => "Wide Focus Zone",
                'Type' => "Lookup",
                0 => "No Zone or AF Failed",
                1 => "Center Zone (Horizontal Orientation)",
                2 => "Center Zone (Vertical Orientation)",
                3 => "Left Zone",
                4 => "Right Zone" ),


49 => array (   'Name' => "Focus Mode",
                'Type' => "Lookup",
                0 => "Auto Focus",
                1 => "Manual Focus" ),


50 => array (   'Name' => "Focus Area",
                'Type' => "Lookup",
                0 => "Wide Focus (normal)",
                1 => "Spot Focus" ),


51 => array (   'Name' => "DEC Switch Position",
                'Type' => "Lookup",
                0 => "Exposure",
                1 => "Contrast",
                2 => "Saturation",
                3 => "Filter" ),



);

/******************************************************************************
* End of Global Variable:     Minolta_Camera_Setting_Definitions
******************************************************************************/




?>
