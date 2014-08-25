<?php

/******************************************************************************
*
* Filename:     canon.php
*
* Description:  Canon Makernote Parser
*               Provides functions to decode an Canon EXIF makernote and to interpret
*               the resulting array into html.
*
*               Canon Makernote Format:
*
*               Field           Size            Description
*               ----------------------------------------------------------------
*               IFD Data        Variable        Standard IFD Data using Canon Tags
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

// Add the parser and interpreter functions to the list of Makernote parsers and interpreters.

$GLOBALS['Makernote_Function_Array']['Read_Makernote_Tag'][] = "get_Canon_Makernote";
$GLOBALS['Makernote_Function_Array']['get_Makernote_Text_Value'][] = "get_Canon_Text_Value";
$GLOBALS['Makernote_Function_Array']['Interpret_Makernote_to_HTML'][] = "get_Canon_Makernote_Html";







/******************************************************************************
*
* Function:     get_Canon_Makernote
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

function get_Canon_Makernote( $Makernote_Tag, $EXIF_Array, $filehnd, $Make_Field )
{
        // Check if the Make Field contains the word Canon
        if ( stristr( $Make_Field, "Canon" ) === FALSE )
        {
                // Canon not found in Make Field - can't process this
                return FALSE;
        }
        
        // Seek to the start of the IFD

        fseek($filehnd, $Makernote_Tag['Tiff Offset'] + $Makernote_Tag['Offset']  );

        // Read the IFD(s) into an array
        $Makernote_Tag['Decoded Data'] = read_Multiple_IFDs( $filehnd, $Makernote_Tag['Tiff Offset'], $Makernote_Tag['ByteAlign'], "Canon" );

        // Save some information into the Tag element to aid interpretation
        $Makernote_Tag['Decoded'] = TRUE;
        $Makernote_Tag['Makernote Type'] = "Canon";
        $Makernote_Tag['Makernote Tags'] = "Canon";

        
        // Return the new tag
        return $Makernote_Tag;
}

/******************************************************************************
* End of Function:     get_Canon_Makernote
******************************************************************************/




/******************************************************************************
*
* Function:     get_Canon_Makernote_Html
*
* Description:  Attempts to interpret a makernote into html. Returns false if
*               it is not a makernote that can be processed with this script
*
* Parameters:   Makernote_Tag - the element of an EXIF array containing the
*                               makernote, as returned from get_EXIF_JPEG
*               filename - the name of the JPEG file being processed ( used
*                          by scripts which display embedded thumbnails)
*
*
* Returns:      output - the html representing the makernote
*               FALSE - If this script could not interpret the makernote, or if
*                       an error occured in decoding
*
******************************************************************************/

function get_Canon_Makernote_Html( $Makernote_tag, $filename )
{
        // Check that this makernote uses canon tags
        if ( $Makernote_tag['Makernote Type'] != "Canon" )
        {
                // Makernote doesn't use Canon tags - cant Interpret it
                return FALSE;
        }

        // Interpret the IFD to html
        return interpret_IFD( $Makernote_tag['Decoded Data'][0], $filename );

}

/******************************************************************************
* End of Function:     get_Canon_Makernote_Html
******************************************************************************/





/******************************************************************************
*
* Function:     get_Canon_Text_Value
*
* Description:  Provides a text value for any tag marked as special for makernotes
*               that this script can decode. Returns false if this is not a makernote
*               that can be processed with this script
*
* Parameters:   Exif_Tag - the element of an the Makernote array containing the
*                          tag in question, as returned from get_Canon_Makernote
*               Tag_Definitions_Name - The name of the Tag Definitions group
*                                      within the global array IFD_Tag_Definitions
*
*
* Returns:      output - the text value for the tag
*               FALSE - If this script could not decode the makernote, or if
*                       an error occured in decoding
*
******************************************************************************/

function get_Canon_Text_Value( $Exif_Tag, $Tag_Definitions_Name )
{

        // Check that the tag uses Canon Definitions
        if ( $Tag_Definitions_Name != "Canon" )
        {
                // Tag doesn't use Canon definintions - can't process it
                return FALSE;
        }


        $Tag_ID = $Exif_Tag['Tag Number'];


        // Process the special tag according to the tag number
        switch ( $Tag_ID )
        {

                // CAMERA SETTINGS 1
                case 1:
                        // Create an output string
                        $output_str = "";

                        // Cycle through each of the camera settings Values
                        foreach( $Exif_Tag['Data'] as $offset => $value )
                        {
                                // Check that the value exists
                                if ( $value !== NULL )
                                {
                                        // Process the settings according to their offset
                                        if ( $offset == 0 )
                                        {
                                                // Do Not Show this Field ( Number of Bytes in Tag )
                                        }
                                        else if ( $offset == 2 )
                                        {
                                                if ( $value == 0 )
                                                {
                                                        $output_str .= "Self timer not used\n";
                                                }
                                                else
                                                {
                                                        $output_str .= "Self timer length : ". ($value/10) . " seconds\n";
                                                }
                                        }
                                        else if ( ( $offset == 23 ) && ( $Exif_Tag['Data'][25] != 0 ))
                                        {
                                                $output_str .= "Maximum Focal Length of Lens: " . ($value / $Exif_Tag['Data'][25]) . "mm\n";
                                        }
                                        else if ( ( $offset == 24 ) && ( $Exif_Tag['Data'][25] != 0 ))
                                        {
                                                $output_str .= "Minimum Focal Length of Lens: " . ($value / $Exif_Tag['Data'][25]) . "mm\n";
                                        }
                                        else if ( $offset == 25 )
                                        {
                                                // Do Not Show this Field ( Focal Length units per mm )
                                        }
                                        else if ( $offset == 29 )
                                        {
                                                if ( $value & 0x4000 == 0x4000 )
                                                {
                                                        $output_str .= "External E-TTL Flash\n";
                                                }
                                                if ( $value & 0x2000 == 0x2000 )
                                                {
                                                        $output_str .= "Internal Flash\n";
                                                }
                                                if ( $value & 0x0800 == 0x0800 )
                                                {
                                                        $output_str .= "Flash FP sync used\n";
                                                }
                                                if ( $value & 0x0080 == 0x0080 )
                                                {
                                                        $output_str .= "Second (Rear) curtain flash sync used\n";
                                                }
                                                if ( $value & 0x0008 == 0x0008 )
                                                {
                                                        $output_str .= "Flash FP sync enabled\n";
                                                }

                                        }
                                        else if ( array_key_exists( $offset, $GLOBALS[ "Canon_Camera_Settings_1_Tag_Values" ] ) )
                                        {
                                                if ( array_key_exists( $value, $GLOBALS[ "Canon_Camera_Settings_1_Tag_Values" ][$offset] ) )
                                                {
                                                        $output_str .= $GLOBALS[ "Canon_Camera_Settings_1_Tag_Values" ][$offset]['Name'] . ": " . $GLOBALS[ "Canon_Camera_Settings_1_Tag_Values" ][$offset][$value] . "\n";
                                                }
                                                else
                                                {
                                                        if ( $GLOBALS['HIDE_UNKNOWN_TAGS'] == FALSE )
                                                        {
                                                                $output_str .= $GLOBALS[ "Canon_Camera_Settings_1_Tag_Values" ][$offset]['Name'] . ": Unknown Value ($value)\n";
                                                        }
                                                }
                                        }
                                        else
                                        {
                                                if ( $GLOBALS['HIDE_UNKNOWN_TAGS'] == FALSE )
                                                {
                                                        // Unknown Canon camera setting
                                                        $output_str .= "        Unknown Setting ($offset), value: $value\n";
                                                }
                                        }
                                }

                        }
                        // Return the text string
                        return $output_str;
                        break;


                // CAMERA SETTINGS 2
                case 4:
                        // Create an output string
                        $output_str = "";

                        // Cycle through each of the camera settings Values
                        foreach( $Exif_Tag['Data'] as $offset => $value )
                        {
                                // Check that the value exists
                                if ( $value !== NULL )
                                {
                                        // Process the settings according to their offset
                                        if ( $offset == 0 )
                                        {
                                                // Do Not Show this Field ( Number of Bytes in Tag )
                                        }
                                        else if ( $offset == 9 )
                                        {
                                                $output_str .= "Sequence Number in a continuous burst : $value\n";
                                        }
                                        else if ( $offset == 14 )
                                        {
                                                $output_str .= "Number of Focus Points Available: ". ( ( $value & 0xF000 ) / 0x1000 ) . "\n";

                                                if ( $value & 0x0004 == 0x0004 )
                                                {
                                                        $output_str .= "Left Focus Point Used\n";
                                                }
                                                if ( $value & 0x0002 == 0x0002 )
                                                {
                                                        $output_str .= "Centre Focus Point Used\n";
                                                }
                                                if ( $value & 0x0001 == 0x0001 )
                                                {
                                                        $output_str .= "Right Focus Point Used\n";
                                                }
                                        }
                                        else if ( $offset == 19 )
                                        {
                                                $output_str .= "Subject distance: $value (units either mm or cm)\n";
                                        }
                                        else if ( array_key_exists( $offset, $GLOBALS[ "Canon_Camera_Settings_2_Tag_Values" ] ) )
                                        {
                                                if ( array_key_exists( $value, $GLOBALS[ "Canon_Camera_Settings_2_Tag_Values" ][$offset] ) )
                                                {
                                                        $output_str .= $GLOBALS[ "Canon_Camera_Settings_2_Tag_Values" ][$offset]['Name'] . ": " . $GLOBALS[ "Canon_Camera_Settings_2_Tag_Values" ][$offset][$value] . "\n";
                                                }
                                                else
                                                {
                                                        if ( $GLOBALS['HIDE_UNKNOWN_TAGS'] == FALSE )
                                                        {
                                                                $output_str .= $GLOBALS[ "Canon_Camera_Settings_2_Tag_Values" ][$offset]['Name'] . ": Unknown Value ($value)\n";
                                                        }
                                                }
                                        }
                                        else
                                        {
                                                if ( $GLOBALS['HIDE_UNKNOWN_TAGS'] == FALSE )
                                                {
                                                        $output_str .= "        Unknown Setting ($offset), value: $value\n";
                                                }
                                        }
                                }

                        }
                        // Return the text string
                        return $output_str;
                        break;
                        
                        
                // Serial Number
                case 12:
                        $output_str =  sprintf ( "%04X%05d", (($Exif_Tag['Data'][0] & 0xFF00)/256), ($Exif_Tag['Data'][0] & 0x00FF) );
                        break;


                // Custom Functions
                case 15:
                        // Create an output string
                        $output_str = "";

                        // The size element is the first of the value array
                        // get rid of it
                        $tmparray = $Exif_Tag['Data'];
                        array_shift ( $tmparray );

                        // Cycle through each of the custom functions
                        foreach( $tmparray as $valorder => $value )
                        {
                                // Figure out the function number and value
                                $funcno = ( $value & 0xFF00 ) / 256;
                                $funcval = $value & 0x00FF;

                                // Check if the function exists in the lookup table of custom functions
                                if ( array_key_exists( $funcno, $GLOBALS[ "Canon_Custom_Functions_Tag_Values" ] ) )
                                {
                                        // Function Exists in lookup table,
                                        // Check if value exists for this function in the lookup table
                                        if ( array_key_exists( $funcval, $GLOBALS[ "Canon_Custom_Functions_Tag_Values" ][$funcno] ) )
                                        {
                                                // Value exists - Add it to the output text
                                                $output_str .= $GLOBALS[ "Canon_Custom_Functions_Tag_Values" ][$funcno]['Name'] . ": " . $GLOBALS[ "Canon_Custom_Functions_Tag_Values" ][$funcno][$funcval] . "\n";
                                        }
                                        else
                                        {
                                                if ( $GLOBALS['HIDE_UNKNOWN_TAGS'] == FALSE )
                                                {
                                                        // Value doesn't exist - Add a message to the output text
                                                        $output_str .= $GLOBALS[ "Canon_Custom_Functions_Tag_Values" ][$funcno]['Name'] . ": Unknown Value ($value)\n";
                                                }
                                        }
                                }
                                else
                                {
                                        if ( $GLOBALS['HIDE_UNKNOWN_TAGS'] == FALSE )
                                        {
                                                // Function doesn't exist in lookup table - add a message to the output text
                                                $output_str .= "Unknown Custom Function ($funcno), value: $funcval\n";
                                        }
                                }
                        }
                        // Return the resulting string
                        return $output_str;
                        break;

                default :
                        return FALSE;
        }
        
        return FALSE;
}

/******************************************************************************
* End of Function:     get_Canon_Text_Value
******************************************************************************/
















/******************************************************************************
* Global Variable:      IFD_Tag_Definitions, Canon
*
* Contents:     This global variable provides definitions of the known Canon
*               Makernote tags, indexed by their tag number.
*
******************************************************************************/

$GLOBALS[ "IFD_Tag_Definitions" ]['Canon'] = array(

1 => array(     'Name' => "Camera Settings 1",
                'Type' => "Special" ),

4 => array(     'Name' => "Camera Settings 2",
                'Type' => "Special" ),

6 => array(     'Name' => "Image Type",
                'Type' => "String" ),

7 => array(     'Name' => "Firmware Version",
                'Type' => "String" ),

8 => array(     'Name' => "Image Number",
                'Type' => "Numeric" ),

9 => array(     'Name' => "Owner Name",
                'Type' => "String" ),

12 => array(    'Name' => "Camera Serial Number",
                'Type' => "Special" ),

15 => array(    'Name' => "Custom Functions",
                'Type' => "Special" )

);


/******************************************************************************
* End of Global Variable:     IFD_Tag_Definitions, Canon
******************************************************************************/


















/******************************************************************************
* Global Variable:      Canon_Camera_Settings_1_Tag_Values
*
* Contents:     This global variable provides definitions for the Canon Camera
*               Settings 1 Makernote tag, indexed by their offset.
*
******************************************************************************/

$GLOBALS[ "Canon_Camera_Settings_1_Tag_Values" ] = array(

1 => array(     'Name' => "Macro Mode",
                1 => "Macro",
                2 => "Normal ( Not Macro )" ),

3 => array(     'Name' => "Quality",
                2 => "Normal",
                3 => "Fine",
                5 => "Superfine" ),

4 => array(     'Name' => "Flash Mode",
                0 => "Flash Not Fired",
                1 => "Auto",
                2 => "On",
                3 => "Red Eye Reduction",
                4 => "Slow Synchro",
                5 => "Auto + Red Eye Reduction",
                6 => "On + Red Eye Reduction",
                16 => "External Flash" ),

5 => array(     'Name' => "Continuous drive mode",
                0 => "Single Frame or Timer Mode",
                1 => "Continuous" ),

7 => array(     'Name' => "Focus Mode",
                0 => "One-Shot",
                1 => "AI Servo",
                2 => "AI Focus",
                3 => "Manual Focus",
                4 => "Single",
                5 => "Continuous",
                6 => "Manual Focus" ),

10 => array(    'Name' => "Image Size",
                0 => "Large",
                1 => "Medium",
                2 => "Small" ),

11 => array(    'Name' => "Easy shooting Mode",
                0 => "Full Auto",
                1 => "Manual",
                2 => "Landscape",
                3 => "Fast Shutter",
                4 => "Slow Shutter",
                5 => "Night",
                6 => "Black & White",
                7 => "Sepia",
                8 => "Portrait",
                9 => "Sports",
                10 => "Macro / Close-Up",
                11 => "Pan Focus" ),


12 => array(    'Name' => "Digital Zoom",
                0 => "No Digital Zoom",
                1 => "2x",
                2 => "4x" ),

13 => array(    'Name' => "Contrast",
                0 => "Normal",
                1 => "High",
                65535 => "Low" ),

14 => array(    'Name' => "Saturation",
                0 => "Normal",
                1 => "High",
                65535 => "Low" ),

15 => array(    'Name' => "Sharpness",
                0 => "Normal",
                1 => "High",
                65535 => "Low" ),

16 => array(    'Name' => "ISO Speed",
                0 => "Check ISOSpeedRatings EXIF tag for ISO Speed",
                15 => "Auto ISO",
                16 => "ISO 50",
                17 => "ISO 100",
                18 => "ISO 200",
                19 => "ISO 400" ),

17 => array(    'Name' => "Metering Mode",
                3 => "Evaluative",
                4 => "Partial",
                5 => "Centre Weighted" ),

18 => array(    'Name' => "Focus Type",
                0 => "Manual",
                1 => "Auto",
                3 => "Close-up (Macro)",
                8 => "Locked (Pan Mode)" ),

19 => array(    'Name' => "Auto Focus Point Selected",
                12288 => "None (Manual Focus)",
                12289 => "Auto Selected",
                12290 => "Right",
                12291 => "Centre",
                12292 => "Left" ),

20 => array(    'Name' => "Exposure Mode",
                0 => "Easy Shooting (See Easy Shooting Mode)",
                1 => "Program",
                2 => "Tv-Priority",
                3 => "Av-Priority",
                4 => "Manual",
                5 => "A-DEP" ),

28 => array(    'Name' => "Flash Activity",
                0 => "Flash Did Not Fire",
                1 => "Flash Fired" ),

32 => array(    'Name' => "Focus Mode",
                0 => "Focus Mode: Single",
                1 => "Focus Mode: Continuous" )

);

/******************************************************************************
* End of Global Variable:     Canon_Camera_Settings_1_Tag_Values
******************************************************************************/




/******************************************************************************
* Global Variable:      Canon_Camera_Settings_2_Tag_Values
*
* Contents:     This global variable provides definitions for the Canon Camera
*               Settings 2 Makernote tag, indexed by their offset.
*
******************************************************************************/

$GLOBALS[ "Canon_Camera_Settings_2_Tag_Values" ] = array(

7 => array (    'Name' => "White Balance",
                0 => "Auto",
                1 => "Sunny",
                2 => "Cloudy",
                3 => "Tungsten",
                4 => "Flourescent",
                5 => "Flash",
                6 => "Custom" ),

15 => array(    'Name' => "Flash Bias",
                0xffc0 => "-2 EV",
                0xffcc => "-1.67 EV",
                0xffd0 => "-1.5 EV",
                0xffd4 => "-1.33 EV",
                0xffe0 => "-1 EV",
                0xffec => "-0.67 EV",
                0xfff0 => "-0.5 EV",
                0xfff4 => "-0.33 EV",
                0x0000 => "0 EV",
                0x000c => "0.33 EV",
                0x0010 => "0.5 EV",
                0x0014 => "0.67 EV",
                0x0020 => "1 EV",
                0x002c => "1.33 EV",
                0x0030 => "1.5 EV",
                0x0034 => "1.67 EV",
                0x0040 => "2 EV" ),
);

/******************************************************************************
* End of Global Variable:     Canon_Camera_Settings_2_Tag_Values
******************************************************************************/






/******************************************************************************
* Global Variable:      Canon_Custom_Functions_Tag_Values
*
* Contents:     This global variable provides definitions for the Canon Custom
*               Functions Makernote tag, indexed by their offset.
*
******************************************************************************/

$GLOBALS[ "Canon_Custom_Functions_Tag_Values" ] = array(

1 => array (    'Name' => "Long Exposure Noise Reduction",
                0 => "Off",
                1 => "On" ),

2 => array (    'Name' => "Shutter/Auto Exposure-lock buttons",
                0 => "AF/AE lock",
                1 => "AE lock/AF",
                2 => "AF/AF lock",
                3 => "AE+release/AE+AF" ),

3 => array (    'Name' => "Mirror lockup",
                0 => "Disable",
                1 => "Enable" ),

4 => array (    'Name' => "Tv/Av and exposure level",
                0 => "1/2 stop",
                1 => "1/3 stop" ),

5 => array (    'Name' => "AF-assist light",
                0 => "On (Auto)",
                1 => "Off" ),

6 => array (    'Name' => "Shutter speed in Av mode",
                0 => "Automatic",
                1 => "1/200 (fixed)" ),

7 => array (    'Name' => "Auto-Exposure Bracketting sequence/auto cancellation",
                0 => "0,-,+ / Enabled",
                1 => "0,-,+ / Disabled",
                2 => "-,0,+ / Enabled",
                3 => "-,0,+ / Disabled" ),

8 => array (    'Name' => "Shutter Curtain Sync",
                0 => "1st Curtain Sync",
                1 => "2nd Curtain Sync" ),

9 => array (    'Name' => "Lens Auto-Focus stop button Function Switch",
                0 => "AF stop",
                1 => "Operate AF",
                2 => "Lock AE and start timer" ),

10 => array (   'Name' => "Auto reduction of fill flash",
                0 => "Enable",
                1 => "Disable" ),

11 => array (   'Name' => "Menu button return position",
                0 => "Top",
                1 => "Previous (volatile)",
                2 => "Previous" ),

12 => array (   'Name' => "SET button function when shooting",
                0 => "Not Assigned",
                1 => "Change Quality",
                2 => "Change ISO Speed",
                3 => "Select Parameters" ),

13 => array (   'Name' => "Sensor cleaning",
                0 => "Disable",
                1 => "Enable" )


);

/******************************************************************************
* End of Global Variable:     Canon_Custom_Functions_Tag_Values
******************************************************************************/


?>
