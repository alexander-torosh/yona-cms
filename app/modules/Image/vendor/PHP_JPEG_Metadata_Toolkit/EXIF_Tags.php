<?php

/******************************************************************************
*
* Filename:     EXIF_Tags.php
*
* Description:  Provides definitions of the tags for TIFF, EXIF, Interoperability,
*               GPS, Meta, Kodak Special Effects and Kodak Borders IFD's.
*
* Author:       Evan Hunter
*
* Date:         1/8/2004
*
* Project:      PHP JPEG Metadata Toolkit
*
* Revision:     1.11
*
* Changes:      1.00 -> 1.11 : Added TIFF compression types ZIP, LZW and JPEG
*                              Added embedded XMP tag
*                              Added embedded Photoshop IRB tag
*                              Fixed GPS tags after testing
*
* URL:          http://electronics.ozhiker.com
*
* Copyright:    Copyright Evan Hunter 2004
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






/******************************************************************************
* Global Variable:      IFD_Tag_Definitions
*
* Contents:     This array defines the fields for the TIFF, EXIF, Interoperability,
*               GPS, Meta, Kodak Special Effects and Kodak Borders IFD's.
*               It is indexed by the IFD Type, then the Tag number
*
******************************************************************************/

$GLOBALS[ "IFD_Tag_Definitions" ] = array(


/*****************************************************************************/
/*                                                                           */
/* TIFF Tags                                                                 */
/*                                                                           */
/*****************************************************************************/


"TIFF" => array(


256 => array(   'Name'  => "Image Width",
                'Description' => "Width of image in pixels (number of columns)",
                'Type'  => "Numeric",
                'Units' => "pixels" ),

257 => array(   'Name'  =>  "Image Length",
                'Description' => "Height of image in pixels (number of rows)",
                'Type'  => "Numeric",
                'Units' => "pixels" ),

258 => array(   'Name'  => "Bits Per Sample",
                'Description' => "Number of bits recorded per sample (a sample is usually one colour (Red, Green or Blue) of one pixel)",
                'Type'  => "Numeric",
                'Units' => "bits ( for each colour component )" ),


259 => array(   'Name' => "Compression",
                'Description' => "Specifies what type of compression is used 1 = uncompressed, 6 = JPEG compression (thumbnails only), Other = reserved",
                'Type' => "Lookup",
                1 => "Uncompressed",
                5 => "LZW Compression",
                6 => "Thumbnail compressed with JPEG compression",
                7 => "JPEG Compression",
                8 => "ZIP Compression" ),                                // Change: Added TIFF compression types as of version 1.11

262 => array(   'Name' =>  "Photometric Interpretation",
                'Description' => "Specifies Pixel Composition - 0 or 1 = monochrome, 2 = RGB, 3 = Palatte Colour, 4 = Transparency Mask, 6 = YCbCr",
                'Type' => "Lookup",
                2 => "RGB (Red Green Blue)",
                6 => "YCbCr (Luminance, Chroma minus Blue, and Chroma minus Red)" ),

274 => array(   'Name' =>  "Orientation",
                'Description' => "Specifies the orientation of the image.\n
1 = Row 0 top, column 0 left\n
2 = Row 0 top, column 0 right\n
3 = Row 0 bottom, column 0 right\n
4 = Row 0 bottom, column 0 left\n
5 = Row 0 left, column 0 top\n
6 = Row 0 right, column 0 top\n
7 = Row 0 right, column 0 bottom\n
8 = Row 0 left, column 0 bottom",
                'Type' => "Lookup",
                1 => "No Rotation, No Flip \n(Row 0 is at the visual top of the image,\n and column 0 is the visual left-hand side)",
                2 => "No Rotation, Flipped Horizontally \n(Row 0 is at the visual top of the image,\n and column 0 is the visual right-hand side)",
                3 => "Rotated 180 degrees, No Flip \n(Row 0 is at the visual bottom of the image,\n and column 0 is the visual right-hand side)",
                4 => "No Rotation, Flipped Vertically \n(Row 0 is at the visual bottom of the image,\n and column 0 is the visual left-hand side)",
                5 => "Flipped Horizontally, Rotated 90 degrees counter clockwise \n(Row 0 is at the visual left-hand side of of the image,\n and column 0 is the visual top)",
                6 => "No Flip, Rotated 90 degrees clockwise \n(Row 0 is at the visual right-hand side of of the image,\n and column 0 is the visual top)",
                7 => "Flipped Horizontally, Rotated 90 degrees clockwise \n(Row 0 is at the visual right-hand side of of the image,\n and column 0 is the visual bottom)",
                8 => "No Flip, Rotated 90 degrees counter clockwise \n(Row 0 is at the visual left-hand side of of the image,\n and column 0 is the visual bottom)" ),
277 => array(   'Name' =>  "Samples Per Pixel",
                'Description' => "Number of recorded samples (colours) per pixel - usually 1 for B&W, grayscale, and palette-colour, usually 3 for RGB and YCbCr",
                'Type' => "Numeric",
                'Units' => "Components (colours)" ),

284 => array(   'Name' =>  "Planar Configuration",
                'Description' => "Specifies whether pixel components are recorded in chunky or planar format - 1 = Chunky, 2 = Planar",
                'Type' => "Lookup",
                1 => "Chunky Format",
                2 => "Planar Format" ),

530 => array(   'Name' =>  "YCbCr Sub-Sampling",
                'Description' => "Specifies ratio of chrominance to luminance components - [2, 1] = YCbCr4:2:2,  [2, 2] = YCbCr4:2:0",
                'Type' => "Special" ),


531 => array(   'Name' =>  "YCbCr Positioning",
                'Description' => "Specifies location of chrominance and luminance components - 1 = centered, 2 = co-sited",
                'Type' => "Lookup",
                1 => "Chrominance components Centred in relation to luminance components",
                2 => "Chrominance and luminance components Co-Sited" ),


282 => array(   'Name' =>  "X Resolution",
                'Description' => "Number of columns (pixels) per \'ResolutionUnit\'",
                'Type' => "Numeric",
                'Units'=> "pixels per 'Resolution Unit' " ),

283 => array(   'Name' =>  "Y Resolution",
                'Description' => "Number of rows (pixels) per \'ResolutionUnit\'",
                'Type' => "Numeric",
                'Units'=> "pixels per 'Resolution Unit' " ),

296 => array(   'Name' =>  "Resolution Unit",
                'Description' => "Units for measuring XResolution and YResolution - 1 = No units, 2 = Inches, 3 = Centimetres",
                'Type' => "Lookup",
                2 => "Inches",
                3 => "Centimetres" ),

273 => array(   'Name' =>  "Strip Offsets",
                'Type' => "Numeric",
                'Units'=> "bytes offset" ),

278 => array(   'Name' =>  "Rows Per Strip",
                'Type' => "Numeric",
                'Units'=> "rows" ),

279 => array(   'Name' => "Strip Byte Counts",
                'Type' => "Numeric",
                'Units'=> "bytes" ),

513 => array(   'Name' => "Exif Thumbnail (JPEG Interchange Format)",
                'Type' => "Special" ),

514 => array(   'Name' => "Exif Thumbnail Length (JPEG Interchange Format Length)",
                'Type' => "Numeric",
                'Units'=> "bytes" ),

301 => array(   'Name' => "Transfer Function",
                'Type' => "Numeric",
                'Units'=> "" ),

318 => array(   'Name' => "White Point Chromaticity",
                'Type' => "Numeric",
                'Units'=> "(x,y coordinates on a 1931 CIE xy chromaticity diagram)" ),

319 => array(   'Name' => "Primary Chromaticities",
                'Type' => "Numeric",
                'Units'=> "(Red x,y, Green x,y, Blue x,y coordinates on a 1931 CIE xy chromaticity diagram)" ),

529 => array(   'Name' => "YCbCr Coefficients",
                'Description' => "Transform Coefficients for transformation from RGB to YCbCr",
                'Type' => "Numeric",
                'Units'=> "(LumaRed, LumaGreen, LumaBlue [proportions of red, green, and blue in luminance])" ),

532 => array(   'Name' => "Reference Black point and White point",
                'Type' => "Numeric",
                'Units'=> "(R or Y White Headroom, R or Y Black Footroom, G or Cb White Headroom, G or Cb Black Footroom, B or Cr White Headroom, B or Cr Black Footroom)" ),

306 => array(   'Name' => "Date and Time",
                'Type' => "Numeric",
                'Units'=> " (Format: YYYY:MM:DD HH:mm:SS)" ),

270 => array(   'Name' => "Image Description",
                'Type' => "String" ),

271 => array(   'Name' => "Make (Manufacturer)",
                'Type' => "String" ),

272 => array(   'Name' => "Model",
                'Type' => "String" ),

305 => array(   'Name' => "Software or Firmware",
                'Type' => "String" ),

315 => array(   'Name' => "Artist Name",
                'Type' => "String" ),

700 => array(   'Name' => "Embedded XMP Block",        // Change: Added embedded XMP as of version 1.11
                'Type' => "XMP" ),

33432 => array( 'Name' => "Copyright Information",
                'Type' => "String" ),

34665 => array( 'Name' => "EXIF Image File Directory (IFD)",
                'Type' => "SubIFD",
                'Tags Name' => "EXIF" ),

33723 => array( 'Name' => "IPTC Records",
                'Type' => "IPTC" ),

34377 => array( 'Name' => "Embedded Photoshop IRB",    // Change: Added embedded IRB as of version 1.11
                'Type' => "IRB" ),

34853 => array( 'Name' => "GPS Info Image File Directory (IFD)",        // Change: Moved GPS IFD tag to correct location as of version 1.11
                'Type' => "SubIFD",
                'Tags Name' => "GPS" ),

50341 => array( 'Name' => "Print Image Matching Info",
                'Type' => "PIM" ),

),


/*****************************************************************************/
/*                                                                           */
/* EXIF Tags                                                                 */
/*                                                                           */
/*****************************************************************************/


'EXIF' => array (

// Exif IFD
36864 => array( 'Name' => "Exif Version",
                'Type' => "String" ),

40965 => array( 'Name' => "Interoperability Image File Directory (IFD)",
                'Type' => "SubIFD",
                'Tags Name' => "Interoperability" ),

// Change: removed GPS IFD tag from here as it was incorrect location - as of version 1.11

40960 => array( 'Name' => "FlashPix Version",
                'Type' => "String" ),

40961 => array( 'Name' => "Colour Space",
                'Type' => "Lookup",
                1 => "sRGB",
                0xFFFF => "Uncalibrated" ),

40962 => array( 'Name' => "Pixel X Dimension",
                'Type' => "Numeric",
                'Units'=> "pixels" ),

40963 => array( 'Name' => "Pixel Y Dimension",
                'Type' => "Numeric",
                'Units' => "pixels" ),

37121 => array( 'Name' => "Components Configuration",
                'Type' => "Special" ),

37122 => array( 'Name' => "Compressed Bits Per Pixel",
                'Type' => "Numeric",
                'Units' => "bits" ),

37500 => array( 'Name' => "Maker Note",
                'Type' => "Maker Note" ),

37510 => array( 'Name' => "User Comment",
                'Type' => "Character Coded String" ),

40964 => array( 'Name' => "Related Sound File",
                'Type' => "String" ),

36867 => array( 'Name' => "Date and Time of Original",
                'Type' => "String",
                'Units' => " (Format: YYYY:MM:DD HH:mm:SS)" ),

36868 => array( 'Name' => "Date and Time when Digitized",
                'Type' => "String",
                'Units' => " (Format: YYYY:MM:DD HH:mm:SS)" ),

37520 => array( 'Name' => "Sub Second Time",
                'Type' => "String" ),

37521 => array( 'Name' => "Sub Second Time of Original",
                'Type' => "String" ),

37522 => array( 'Name' => "Sub Second Time when Digitized",
                'Type' => "String" ),

33434 => array( 'Name' => "Exposure Time",
                'Type' => "Numeric",
                'Units' => "seconds" ),

37377 => array( 'Name' => "APEX Shutter Speed Value (Tv)",
                'Type' => "Numeric" ),

37378 => array( 'Name' => "APEX Aperture Value (Av)",
                'Type' => "Numeric" ),

37379 => array( 'Name' => "APEX Brightness Value (Bv)",
                'Type' => "Numeric" ),

37380 => array( 'Name' => "APEX Exposure Bias Value (Exposure Compensation)",
                'Type' => "Numeric",
                'Units' => "EV" ),

42240 => array( 'Name' => "Gamma Compensation for Playback",
                'Type' => "Numeric" ),


37381 => array( 'Name' => "APEX Maximum Aperture Value",
                'Type' => "Numeric" ),

37382 => array( 'Name' => "Subject Distance",
                'Type' => "Numeric",
                'Units' => "metres" ),

37383 => array( 'Name' => "Metering Mode",
                'Type' => "Lookup",
                0 => "Unknown",
                1 => "Average",
                2 => "Center Weighted Average",
                3 => "Spot",
                4 => "Multi Spot",
                5 => "Pattern",
                6 => "Partial",
                255 => "Other" ),

37384 => array( 'Name' => "Light Source",
                'Type' => "Lookup",
                0 => "Unknown",
                1 => "Daylight",
                2 => "Fluorescent",
                3 => "Tungsten (incandescent light)",
                4 => "Flash",
                9 => "Fine weather",
                10 => "Cloudy weather",
                11 => "Shade",
                12 => "Daylight fluorescent (D 5700 – 7100K)",
                13 => "Day white fluorescent (N 4600 – 5400K)",
                14 => "Cool white fluorescent (W 3900 – 4500K)",
                15 => "White fluorescent (WW 3200 – 3700K)",
                17 => "Standard light A",
                18 => "Standard light B",
                19 => "Standard light C",
                20 => "D55",
                21 => "D65",
                22 => "D75",
                23 => "D50",
                24 => "ISO studio tungsten",
                255 => "Other" ),

37385 => array( 'Name' => "Flash",
                'Type' => "Lookup",
                0  => "Flash did not fire",
                1  => "Flash fired",
                5  => "Strobe return light not detected",
                7  => "Strobe return light detected",
                9  => "Flash fired, compulsory flash mode",
                13 => "Flash fired, compulsory flash mode, return light not detected",
                15 => "Flash fired, compulsory flash mode, return light detected",
                16 => "Flash did not fire, compulsory flash suppression mode",
                24 => "Flash did not fire, auto mode",
                25 => "Flash fired, auto mode",
                29 => "Flash fired, auto mode, return light not detected",
                31 => "Flash fired, auto mode, return light detected",
                32 => "No flash function",
                65 => "Flash fired, red-eye reduction mode",
                69 => "Flash fired, red-eye reduction mode, return light not detected",
                71 => "Flash fired, red-eye reduction mode, return light detected",
                73 => "Flash fired, compulsory flash mode, red-eye reduction mode",
                77 => "Flash fired, compulsory flash mode, red-eye reduction mode, return light not detected",
                79 => "Flash fired, compulsory flash mode, red-eye reduction mode, return light detected",
                89 => "Flash fired, auto mode, red-eye reduction mode",
                93 => "Flash fired, auto mode, return light not detected, red-eye reduction mode",
                95 => "Flash fired, auto mode, return light detected, red-eye reduction mode" ),

37386 => array( 'Name' => "FocalLength",
                'Type' => "Numeric",
                'Units' => "mm" ),

37396 => array( 'Name' => "Subject Area",
                'Type' => "Numeric",
                'Units' => "( Two Values: x,y coordinates,  Three Values: x,y coordinates, diameter,  Four Values: center x,y coordinates, width, height)" ),

33437 => array( 'Name' => "Aperture F Number",
                'Type' => "Numeric" ),

34850 => array( 'Name' => "Exposure Program",
                'Type' => "Lookup",
                0 => "Not defined",
                1 => "Manual",
                2 => "Normal program",
                3 => "Aperture priority",
                4 => "Shutter priority",
                5 => "Creative program (biased toward depth of field)",
                6 => "Action program (biased toward fast shutter speed)",
                7 => "Portrait mode (for closeup photos with the background out of focus)",
                8 => "Landscape mode (for landscape photos with the background in focus)" ),

34852 => array( 'Name' => "Spectral Sensitivity",
                'Type' => "String" ),

34855 => array( 'Name' => "ISO Speed Ratings",
                'Type' => "Numeric" ),

34856 => array( 'Name' => "Opto-Electronic Conversion Function",
                'Type' => "Unknown" ),

41483 => array( 'Name' => "Flash Energy",
                'Type' => "Numeric",
                'Units' => "Beam Candle Power Seconds (BCPS)" ),

41484 => array( 'Name' => "Spatial Frequency Response",
                'Type' => "Unknown" ),

41486 => array( 'Name' => "Focal Plane X Resolution",
                'Type' => "Numeric",
                'Units' => "pixels per 'Focal Plane Resolution Unit'" ),

41487 => array( 'Name' => "Focal Plane Y Resolution",
                'Type' => "Numeric",
                'Units' => "pixels per 'Focal Plane Resolution Unit'" ),

41488 => array( 'Name' => "Focal Plane Resolution Unit",
                'Type' => "Lookup",
                2 => "Inches",
                3 => "Centimetres" ),

41492 => array( 'Name' => "Subject Location",
                'Type' => "Numeric",
                'Units' => "(x,y pixel coordinates of subject)" ),

41493 => array( 'Name' => "Exposure Index",
                'Type' => "Numeric" ),

41495 => array( 'Name' => "Sensing Method",
                'Type' => "Lookup",
                1 => "Not defined",
                2 => "One-chip colour area sensor",
                3 => "Two-chip colour area sensor",
                4 => "Three-chip colour area sensor",
                5 => "Colour sequential area sensor",
                7 => "Trilinear sensor",
                8 => "Colour sequential linear sensor" ),

41728 => array( 'Name' => "File Source",
                'Type' => "Lookup",
                3 => "Digital Still Camera" ),

41729 => array( 'Name' => "Scene Type",
                'Type' => "Lookup",
                1 => "A directly photographed image" ),

41730 => array( 'Name' => "Colour Filter Array Pattern",
                'Type' => "Special" ),

41985 => array( 'Name' => "Special Processing (Custom Rendered)",
                'Type' => "Lookup",
                0 => "Normal process",
                1 => "Custom process" ),

41986 => array( 'Name' => "Exposure Mode",
                'Type' => "Lookup",
                0 => "Auto exposure",
                1 => "Manual exposure",
                2 => "Auto bracket" ),

41987 => array( 'Name' => "White Balance",
                'Type' => "Lookup",
                0 => "Auto white balance",
                1 => "Manual white balance" ),

41988 => array( 'Name' => "Digital Zoom Ratio",
                'Type' => "Numeric",
                'Units' => " ( Zero = Digital Zoom Not Used )" ),

41989 => array( 'Name' => "Equivalent Focal Length In 35mm Film",
                'Type' => "Numeric",
                'Units' => "mm" ),

41990 => array( 'Name' => "Scene Capture Type",
                'Type' => "Lookup",
                0 => "Standard",
                1 => "Landscape",
                2 => "Portrait",
                3 => "Night scene" ),

41991 => array( 'Name' => "Gain Control",
                'Type' => "Lookup",
                0 => "None",
                1 => "Low gain up",
                2 => "High gain up",
                3 => "Low gain down",
                4 => "High gain down" ),

41992 => array( 'Name' => "Contrast",
                'Type' => "Lookup",
                0 => "Normal",
                1 => "Soft",
                2 => "Hard" ),

41993 => array( 'Name' => "Saturation",
                'Type' => "Lookup",
                0 => "Normal",
                1 => "Low saturation",
                2 => "High saturation" ),

41994 => array( 'Name' => "Sharpness",
                'Type' => "Lookup",
                0 => "Normal",
                1 => "Soft",
                2 => "Hard" ),

41995 => array( 'Name' => "Device Setting Description",
                'Type' => "Unknown" ),

41996 => array( 'Name' => "Subject Distance Range",
                'Type' => "Lookup",
                0 => "Unknown",
                1 => "Macro",
                2 => "Close view",
                3 => "Distant view" ),

42016 => array( 'Name' => "Image Unique ID",
                'Type' => "String" ),



//  11  => "ACDComment",
//  255 => "NewSubfileType"


),




/*****************************************************************************/
/*                                                                           */
/* Interoperability Tags                                                     */
/*                                                                           */
/*****************************************************************************/

"Interoperability" => array(

1 => array(     'Name' => "Interoperability Index",
                'Type' => "String" ),

2 => array(     'Name' => "Interoperability Version",
                'Type' => "String" ),

4096 => array(  'Name' => "Related Image File Format",
                'Type' => "String" ),

4097 => array(  'Name' => "Related Image File Width",
                'Type' => "Numeric",
                'Units' => "pixels" ),

4098 => array(  'Name' => "Related Image File Length",
                'Type' => "Numeric",
                'Units' => "pixels " )

),


/*****************************************************************************/
/*                                                                           */
/* GPS Tags                                                                  */
/*                                                                           */
/*****************************************************************************/

"GPS" => array(

0 => array(     'Name' => "GPS Tag Version",
                'Type' => "Numeric",
                'Units' => "(e.g.: 2.2.0.0 = Version 2.2 )" ),

1 => array(     'Name' => "North or South Latitude",
                'Type' => "String" ),

2 => array(     'Name' => "Latitude",
                'Type' => "Numeric",
                'Units' => "(Degrees Minutes Seconds North or South)" ),

3 => array(     'Name' => "East or West Longitude",
                'Type' => "String" ),

4 => array(     'Name' => "Longitude",
                'Type' => "Numeric",
                'Units' => "(Degrees Minutes Seconds East or West)" ),

5 => array(     'Name' => "Altitude Reference",
                'Type' => "Lookup",
                0 => "Sea Level",
                1 => "Sea level reference (negative value)" ),

6 => array(     'Name' => "Altitude",
                'Type' => "Numeric",
                'Units' => "Metres with respect to Altitude Reference" ),

7 => array(     'Name' => "GPS Time (atomic clock)",
                'Type' => "Numeric",
                'Units' => "(Hours Minutes Seconds)" ),

8 => array(     'Name' => "GPS Satellites used for Measurement",
                'Type' => "String" ),

9 => array(     'Name' => "GPS Receiver Status",
                'Type' => "Lookup",
                'A' => "Measurement in progress",          // Change: Fixed tag values as of version 1.11
                'V' => "Measurement Interoperability" ),

10 => array(    'Name' => "GPS Measurement Mode",
                'Type' => "Lookup",
                2 => "2-dimensional measurement",         // Change: Fixed tag values as of version 1.11
                3 => "3-dimensional measurement" ),

11 => array(    'Name' => "Measurement Precision",
                'Type' => "Numeric",
                'Units' => "(Data Degree of Precision, Horizontal for 2D, Position for 3D)" ),

12 => array(    'Name' => "Speed Unit",
                'Type' => "Lookup",
                'K' => "Kilometers per Hour",            // Change: Fixed tag values as of version 1.11
                'M' => "Miles per Hour",
                'N' => "Knots" ),

13 => array(    'Name' => "Speed of GPS receiver",
                'Type' => "Numeric",
                'Units' => "Speed Units" ),

14 => array(    'Name' => "Reference for direction of Movement",
                'Type' => "Lookup",                     // Change: Fixed tag values as of version 1.11
                'T' => "True North",
                'M' => "Magnetic North" ),

15 => array(    'Name' => "Direction of Movement",
                'Type' => "Numeric",
                'Units' => "Degrees relative to Movement Direction Reference" ),

16 => array(    'Name' => "Reference for Direction of Image",
                'Type' => "Lookup",
                'T' => "True North",                    // Change: Fixed tag values as of version 1.11
                'M' => "Magnetic North" ),

17 => array(    'Name' => "Direction of Image",
                'Type' => "Numeric",
                'Units' => "Degrees relative to Image Direction Reference" ),

18 => array(    'Name' => "Geodetic Survey Datum Used",
                'Type' => "String" ),

19 => array(    'Name' => "Destination - North or South Latitude",
                'Type' => "String" ),

20 => array(    'Name' => "Latitude of Destination",
                'Type' => "Numeric",
                'Units' => "(Degrees Minutes Seconds North or South)" ),

21 => array(    'Name' => "Destination - East or West Longitude",
                'Type' => "String" ),

22 => array(    'Name' => "Longitude of Destination",
                'Type' => "Numeric",
                'Units' => "(Degrees Minutes Seconds East or West)" ),

23 => array(    'Name' => "Reference for Bearing of Destination",
                'Type' => "Lookup",
                'T' => "True North",                    // Change: Fixed tag values as of version 1.11
                'M' => "Magnetic North" ),

24 => array(    'Name' => "Bearing of Destination",
                'Type' => "Numeric",
                'Units' => "Degrees relative to Destination Bearing Reference" ),

25 => array(    'Name' => "Units for Distance to Destination",
                'Type' => "Lookup",
                'K' => "Kilometres",                    // Change: Fixed tag values as of version 1.11
                'M' => "Miles",
                'N' => "Nautical Miles" ),

26 => array(    'Name' => "Distance to Destination",
                'Type' => "Numeric",
                'Units' => "Destination Distance Units" ),

27 => array(    'Name' => "Name of GPS Processing Method",
                'Type' => "Character Coded String" ),

28 => array(    'Name' => "Name of GPS Area",
                'Type' => "Character Coded String" ),

29 => array(    'Name' => "GPS Date",
                'Type' => "Numeric",
                'Units'=> " (Format: YYYY:MM:DD HH:mm:SS)" ),

30 => array(    'Name' => "GPS Differential Correction",
                'Type' => "Lookup",
                0 => "Measurement without differential correction",
                1 => "Differential correction applied" ),

),









/*****************************************************************************/
/*                                                                           */
/* META (App3) Tags                                                          */
/*                                                                           */
/*****************************************************************************/

"Meta" => array(


50000 => array( 'Name' => "CaptureDevice.FilmProductCode",
                'Type' => "Unknown" ),

50001 => array( 'Name' => "DigitalProcess.ImageSourceEK",
                'Type' => "Unknown" ),

50002 => array( 'Name' => "CaptureConditions.PAR",
                'Type' => "Unknown" ),

50003 => array( 'Name' => "CaptureDevice.CameraOwner.EK",
                'Type' => "Character Coded String" ),

50004 => array( 'Name' => "CaptureDevice.SerialNumber.Camera",
                'Type' => "Unknown" ),

50005 => array( 'Name' => "SceneContent.GroupCaption.UserSelectGroupTitle",
                'Type' => "Unknown" ),

50006 => array( 'Name' => "OutputOrder.Information.DealerIDNumber",
                'Type' => "Unknown" ),

50007 => array( 'Name' => "CaptureDevice.FID",
                'Type' => "Unknown" ),

50008 => array( 'Name' => "OutputOrder.Information.EnvelopeNumber",
                'Type' => "Unknown" ),

50009 => array( 'Name' => "OutputOrder.SimpleRenderInst.FrameNumber",
                'Type' => "Unknown" ),

50010 => array( 'Name' => "CaptureDevice.FilmCategory",
                'Type' => "Unknown" ),

50011 => array( 'Name' => "CaptureDevice.FilmGencode",
                'Type' => "Unknown" ),

50012 => array( 'Name' => "CaptureDevice.Scanner.ModelAndVersion",
                'Type' => "Unknown" ),

50013 => array( 'Name' => "CaptureDevice.FilmSize",
                'Type' => "Unknown" ),

50014 => array( 'Name' => "DigitalProcess.History.SBARGBShifts",
                'Type' => "Unknown" ),

50015 => array( 'Name' => "DigitalProcess.History.SBAInputImageColourspace",
                'Type' => "Unknown" ),

50016 => array( 'Name' => "DigitalProcess.History.SBAInputImageBitDepth",
                'Type' => "Unknown" ),

50017 => array( 'Name' => "DigitalProcess.History.SBAExposureRecord",
                'Type' => "Unknown" ),

50018 => array( 'Name' => "DigitalProcess.History.UserAdjSBARGBShifts",
                'Type' => "Unknown" ),

50019 => array( 'Name' => "DigitalProcess.ImageRotationStatus",
                'Type' => "Unknown" ),

50020 => array( 'Name' => "DigitalProcess.RollGuid.Elements",
                'Type' => "Unknown" ),

50021 => array( 'Name' => "ImageContainer.MetadataNumber",
                'Type' => "String" ),

50022 => array( 'Name' => "DigitalProcess.History.EditTagArray",
                'Type' => "Unknown" ),

50023 => array( 'Name' => "CaptureConditions.Magnification",
                'Type' => "Unknown" ),

50028 => array( 'Name' => "CaptureDevice.NativePhysicalXResolution",
                'Type' => "Unknown" ),

50029 => array( 'Name' => "CaptureDevice.NativePhysicalYResolution",
                'Type' => "Unknown" ),

50030 => array( 'Name' => "Kodak Special Effects IFD",
                'Type' => "SubIFD",
                'Tags Name' => "KodakSpecialEffects" ),

50031 => array( 'Name' => "Kodak Borders IFD",
                'Type' => "SubIFD",
                'Tags Name' => "KodakBorders" ),

50042 => array( 'Name' => "CaptureDevice.NativePhysicalResolutionUnit",
                'Type' => "Unknown" ),

50200 => array( 'Name' => "ImageContainer.SourceImageDirectory",
                'Type' => "Unknown" ),

50201 => array( 'Name' => "ImageContainer.SourceImageFileName",
                'Type' => "Unknown" ),

50202 => array( 'Name' => "ImageContainer.SourceImageVolumeName",
                'Type' => "Unknown" ),

50284 => array( 'Name' => "CaptureConditions.PrintQuantity",
                'Type' => "Unknown" ),

50286 => array( 'Name' => "DigitalProcess.ImagePrintStatus",
                'Type' => "Unknown" )

),



/*****************************************************************************/
/*                                                                           */
/* Kodak Special Effects IFD Tags                                            */
/*                                                                           */
/*****************************************************************************/

"KodakSpecialEffects" => array(

0 => array(     'Name' => "Digital Effects Version",
                'Type' => "Numeric" ),

1 => array(     'Name' => "Digital Effects Name",
                'Type' => "Character Coded String" ),

2 => array(     'Name' => "Digital Effects Type",
                'Type' => "Lookup",
                0 => "None Applied" )

),

/*****************************************************************************/
/*                                                                           */
/* Kodak Borders IFD Tags                                                    */
/*                                                                           */
/*****************************************************************************/

"KodakBorders" => array(

0 => array(     'Name' => "Borders Version",
                'Type' => "Numeric" ),

1 => array(     'Name' => "Border Name",
                'Type' => "Character Coded String" ),

2 => array(     'Name' => "Border ID",
                'Type' => "Numeric" ),

3 => array(     'Name' => "Border Location",
                'Type' => "Lookup" ),

4 => array(     'Name' => "Border Type",
                'Type' => "Lookup",
                0 => "None" ),

8 => array(     'Name' => "Watermark Type",
                'Type' => "Lookup",
                0 => "None" )

),

);

/******************************************************************************
* End of Global Variable:     IFD_Tag_Definitions
******************************************************************************/

?>