<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>Virtual Payment Client Example</title>
    <meta http-equiv='Content-Type' content='text/html; charset=utf8'>
    <style type="text/css">
        <!--
        h1 {
            font-family: Arial, sans-serif;
            font-size: 24pt;
            color: #08185A;
            font-weight: 100
        }

        h2.co {
            font-family: Arial, sans-serif;
            font-size: 24pt;
            color: #08185A;
            margin-top: 0.1em;
            margin-bottom: 0.1em;
            font-weight: 100
        }

        h3.co {
            font-family: Arial, sans-serif;
            font-size: 16pt;
            color: #000000;
            margin-top: 0.1em;
            margin-bottom: 0.1em;
            font-weight: 100
        }

        body {
            font-family: Verdana, Arial, sans-serif;
            font-size: 10pt;
            color: #08185A background-color: #FFFFFF
        }

        a:link {
            font-family: Verdana, Arial, sans-serif;
            font-size: 8pt;
            color: #08185A
        }

        a:visited {
            font-family: Verdana, Arial, sans-serif;
            font-size: 8pt;
            color: #08185A
        }

        a:hover {
            font-family: Verdana, Arial, sans-serif;
            font-size: 8pt;
            color: #FF0000
        }

        a:active {
            font-family: Verdana, Arial, sans-serif;
            font-size: 8pt;
            color: #FF0000
        }

        .shade {
            height: 25px;
            background-color: #CED7EF
        }

        tr.title {
            height: 25px;
            background-color: #0074C4
        }

        td {
            font-family: Verdana, Arial, sans-serif;
            font-size: 8pt;
            color: #08185A
        }

        th {
            font-family: Verdana, Arial, sans-serif;
            font-size: 10pt;
            color: #08185A;
            font-weight: bold;
            background-color: #CED7EF;
            padding-top: 0.5em;
            padding-bottom: 0.5em
        }

        .background-image {
            font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
            font-size: 12px;
            width: 100%;
            text-align: left;
            border-collapse: collapse;
            background: url("...") 330px 59px no-repeat;
            margin: 0px;
        }

        .background-image th {
            font-weight: normal;
            font-size: 14px;
            color: #339;
            padding: 12px;
        }

        .background-image td {
            color: #669;
            border-top: 1px solid #fff;
            padding: 9px 12px;
        }

        .background-image tfoot td {
            font-size: 11px;
        }

        .background-image tbody td {
            background: url("./back.png");
        }

        * html
.background-image tbody td {
            filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src = 'table-images/back.png', sizingMethod = 'crop');
            background: none;
        }

        .background-image tbody tr:hover td {
            color: #339;
            background: none;
        }

        .background-image .tb_title {
            font-family: Verdana, Arial, sans-serif;
            color: #08185A;
            background-color: #CED7EF;
            font-size: 14px;
            color: #339;
            padding: 12px;
        }

        -->
    </style>
</head>
<body>
<?php
    date_default_timezone_set('Asia/Krasnoyarsk');
?>
<form action="./do.php" method="post">
    <input type="hidden" name="Title" value="VPC 3-Party"/>
    <table width="100%" align="center" border="0" cellpadding='0'
           cellspacing='0'>
        <tr class="shade">
            <td width="1%">&nbsp;</td>
            <td width="40%" align="right"><strong><em>URL cổng thanh toán - Virtual Payment Client
                URL:&nbsp;</em></strong></td>
            <td width="59%"><input type="text" name="virtualPaymentClientURL"
                                   size="63" value="https://mtf.onepay.vn/vpcpay/vpcpay.op"
                                   maxlength="250"/></td>
        </tr>
    </table>
    <center>
        <table class="background-image" summary="Meeting Results">
            <thead>
            <tr>
                <th scope="col" width="250px">Name</th>
                <th scope="col" width="250px">Input</th>
                <th scope="col" width="250px">Chú thích</th>
                <th scope="col">Description</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><strong><em>Merchant ID</em></strong></td>
                <td><input type="text" name="vpc_Merchant" value="TESTONEPAY" size="20"
                           maxlength="16"/></td>
                <td>Được cấp bởi OnePAY</td>
                <td>Provided by OnePAY</td>
            </tr>
            <tr>
                <td><strong><em>Merchant AccessCode</em></strong></td>
                <td><input type="text" name="vpc_AccessCode" value="6BEB2546"
                           size="20" maxlength="8"/></td>
                <td>Được cấp bởi OnePAY</td>
                <td>Provided by OnePAY</td>
            </tr>
            <tr>
                <td><strong><em>Merchant Transaction Reference</em></strong></td>
                <td><input type="text" name="vpc_MerchTxnRef"
                           value="<?php
                echo date('YmdHis') . rand();
                           ?>" size="20"
                           maxlength="40"/></td>
                <td>ID giao dịch, giá trị phải khác nhau trong mỗi lần thanh(tối đa 40 ký tự)
                    toán
                </td>
                <td>ID Transaction - (unique per transaction) - (max 40 char)</td>
            </tr>
            <tr>
                <td><strong><em>Transaction OrderInfo</em></strong></td>
                <td><input type="text" name="vpc_OrderInfo" value="JSECURETEST01"
                           size="20" maxlength="34"/></td>
                <td>Tên hóa đơn - (tối đa 34 ký tự)</td>
                <td>Order Name will show on payment gateway (max 34 char)</td>
            </tr>
            <tr>
                <td><strong><em>Purchase Amount</em></strong></td>
                <td><input type="text" name="vpc_Amount" value="1000000" size="20"
                           maxlength="10"/></td>
                <td>Số tiền cần thanh toán,Đã được nhân với 100. VD: 1000000=10000VND</td>
                <td>Amount,Multiplied with 100, Ex: 1000000=10000VND</td>
            </tr>
            <tr>
                <td><strong><em>Receipt ReturnURL</em></strong></td>
                <td><input type="text" name="vpc_ReturnURL" size="45"
                           value="http://localhost/domestic_php_v2/source_code/dr.php"
                           maxlength="250"/></td>
                <td>Url nhận kết quả trả về sau khi giao dịch hoàn thành.</td>
                <td>URL for receiving payment result from gateway</td>
            </tr>
            <tr>
                <td><strong><em>VPC Version</em></strong></td>
                <td><input type="text" name="vpc_Version" value="2" size="20"
                           maxlength="8"/></td>
                <td>Phiên bản modul (cố định)</td>
                <td>Version (fixed)</td>
            </tr>
            <tr>
                <td><strong><em>Command Type</em></strong></td>
                <td><input type="text" name="vpc_Command" value="pay" size="20"
                           maxlength="16"/></td>
                <td>Loại request (cố định)</td>
                <td>Command Type(fixed)</td>
            </tr>
            <tr>
                <td><strong><em>Payment Server Display Language Locale</em></strong></td>
                <td><input type="text" name="vpc_Locale" value="en" size="20"
                           maxlength="5"/></td>
                <td>Ngôn ngữ hiện thị trên cổng (vn/en)</td>
                <td>Language use on gateway (vn/en)</td>
            </tr>
            </tbody>
        </table>
        <table class="background-image" summary="Meeting Results">
            <thead>
            <tr>
                <th scope="col" colspan="4">Addition Infomation</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td width="250px"><strong><em>IP address</em></strong></td>
                <td width="300px"><input type="text" name="vpc_TicketNo" maxlength="15"
                                         value="<?php
            echo $_SERVER ['REMOTE_ADDR'];
                                         ?>"/></td>
                <td width="250px">IP khách hàng</td>
                <td>IP Client</td>
            </tr>
            <tr>
                <td><strong><em>Shipping Address</em></strong></td>
                <td><input type="text" name="vpc_SHIP_Street01" value="39A Ngo Quyen" size="20"
                           maxlength="500"/></td>
                <td>Địa chỉ gửi hàng</td>
                <td>Shipping Address</td>
            </tr>
            <tr>
                <td><strong><em>Shipping Province</em></strong></td>
                <td><input type="text" name="vpc_SHIP_Provice" value="Hoan Kiem"
                           size="20" maxlength="50"/></td>
                <td>Quận Huyện(địa chỉ gửi hàng)</td>
                <td>Shipping Province</td>
            </tr>
            <tr>
                <td><strong><em>Shipping City</em></strong></td>
                <td><input type="text" name="vpc_SHIP_City"
                           value="Ha Noi" size="20"
                           maxlength="50"/></td>
                <td>Tỉnh/thành phố (địa chỉ khách hàng)</td>
                <td>Shipping City</td>
            </tr>
            <tr>
                <td><strong><em>Shipping Country</em></strong></td>
                <td><input type="text" name="vpc_SHIP_Country" value="Viet Nam"
                           size="20" maxlength="50"/></td>
                <td>Quốc gia(địa chỉ khách hàng)</td>
                <td>Shipping Country</td>
            </tr>
            <tr>
                <td><strong><em>Customer Phone</em></strong></td>
                <td><input type="text" name="vpc_Customer_Phone" value="840904280949" size="20"
                           maxlength="50"/></td>
                <td>Số điện thoại khách hàng</td>
                <td>Customer Phone</td>
            </tr>
            <tr>
                <td><strong><em>Customer email</em></strong></td>
                <td><input type="text" name="vpc_Customer_Email" size="20"
                           value="support@onepay.vn"
                           maxlength="50"/></td>
                <td>Địa chỉ hòm thư của khách hàng</td>
                <td>Customer email</td>
            </tr>
            <tr>
                <td><strong><em>Customer User Id</em></strong></td>
                <td><input type="text" name="vpc_Customer_Id" value="thanhvt" size="20"
                           maxlength="50"/></td>
                <td>Tên tài khoản khách hàng trên hệ thống</td>
                <td>Customer User Id</td>
            </tr>
            <tr>
                <td><strong><em>Note</em></strong></td>
                <td colspan="2">- Không sử dụng tiếng việt có dấu trong các tham số gửi sang cổng thanh toán<br>- Không
                    sử dụng số tiền lẻ với cổng thanh toán test(ví dụ 0.2 đồng tức amount = 20)
                </td>
                <td colspan="1">- do not use vietnamese with sign. Convert to vietnamese no sign before send it to
                    gateway<br>- do not use decimal for amount for testing (100=1VND -> right; 120=1.2VND -> wrong)
                </td>
            </tr>
            </tbody>
        </table>
		<table class="background-image" summary="Meeting Results">
            <thead>
            <tr>
                <th scope="col" colspan="3">Billing Address</th>
            </tr>
            </thead>
            <tfoot>
            <tr>
                <td align="center" colspan="4"><input type="submit" value="Pay Now!"/></td>
            </tr>
            </tfoot>
            <tbody>
            <tr>
                <td><strong><em>Address</em></strong></td>
                <td><input type="text" name="AVS_Street01" maxlength="500" value="194 Tran Quang Khai"/></td>
                <td>Địa chỉ Phát Ngân Hàng phát hành</td>
            </tr>
            <tr>
                <td width="270"><strong><em>City</em></strong></td>
                <td width="330"><input type="text" name="AVS_City" maxlength="200" value="Hanoi"/></td>
                <td>Thành phố Ngân hàng phát hành</td>
            </tr>
            <tr>
                <td><strong><em>State/Province</em></strong></td>
                <td><input type="text" name="AVS_StateProv" maxlength="200" value="Hoan Kiem"/></td>
                <td>Quận Huyện ngân hàng phát hành</td>
            </tr>
            <tr>
                <td><strong><em>Post/Zip Code</em></strong></td>
                <td><input type="text" name="AVS_PostCode" maxlength="5" value="10000"/></td>
                <td>Mã vùng ngân hàng phát hành</td>
            </tr>
            <tr>
                <td><strong><em>Country</em></strong></td>
                <td>
					<select name="AVS_Country">
						<OPTION VALUE="">--Select Country--</OPTION>
						<OPTION VALUE="AF">Afghanistan</OPTION>
						<OPTION VALUE="AL">Albania</OPTION>
						<OPTION VALUE="DZ">Algeria</OPTION>
						<OPTION VALUE="AS">American Samoa</OPTION>
						<OPTION VALUE="AD">Andorra</OPTION>
						<OPTION VALUE="AO">Angola</OPTION>
						<OPTION VALUE="AI">Anguilla</OPTION>
						<OPTION VALUE="AQ">Antarctica</OPTION>
						<OPTION VALUE="AG">Antigua and Barbuda</OPTION>
						<OPTION VALUE="AR">Argentina</OPTION>
						<OPTION VALUE="AM">Armenia</OPTION>
						<OPTION VALUE="AW">Aruba</OPTION>
						<OPTION VALUE="AU">Australia</OPTION>
						<OPTION VALUE="AT">Austria</OPTION>
						<OPTION VALUE="AZ">Azerbaijan</OPTION>
						<OPTION VALUE="BS">Bahamas</OPTION>
						<OPTION VALUE="BH">Bahrain</OPTION>
						<OPTION VALUE="BD">Bangladesh</OPTION>
						<OPTION VALUE="BB">Barbados</OPTION>
						<OPTION VALUE="BY">Belarus</OPTION>
						<OPTION VALUE="BE">Belgium</OPTION>
						<OPTION VALUE="BZ">Belize</OPTION>
						<OPTION VALUE="BJ">Benin</OPTION>
						<OPTION VALUE="BM">Bermuda</OPTION>
						<OPTION VALUE="BT">Bhutan</OPTION>
						<OPTION VALUE="BO">Bolivia</OPTION>
						<OPTION VALUE="BA">Bosnia and Herzegovina</OPTION>
						<OPTION VALUE="BW">Botswana</OPTION>
						<OPTION VALUE="BV">Bouvet Island</OPTION>
						<OPTION VALUE="BR">Brazil</OPTION>
						<OPTION VALUE="IO">British Indian Ocean Territory</OPTION>
						<OPTION VALUE="VG">British Virgin Islands</OPTION>
						<OPTION VALUE="BN">Brunei</OPTION>
						<OPTION VALUE="BG">Bulgaria</OPTION>
						<OPTION VALUE="BF">Burkina Faso</OPTION>
						<OPTION VALUE="BI">Burundi</OPTION>
						<OPTION VALUE="KH">Cambodia</OPTION>
						<OPTION VALUE="CM">Cameroon</OPTION>
						<OPTION VALUE="CA">Canada</OPTION>
						<OPTION VALUE="CV">Cape Verde</OPTION>
						<OPTION VALUE="KY">Cayman Islands</OPTION>
						<OPTION VALUE="CF">Central African Republic</OPTION>
						<OPTION VALUE="TD">Chad</OPTION>
						<OPTION VALUE="CL">Chile</OPTION>
						<OPTION VALUE="CN">China</OPTION>
						<OPTION VALUE="CX">Christmas Island</OPTION>
						<OPTION VALUE="CC">Cocos Islands</OPTION>
						<OPTION VALUE="CO">Colombia</OPTION>
						<OPTION VALUE="KM">Comoros</OPTION>
						<OPTION VALUE="CG">Congo</OPTION>
						<OPTION VALUE="CK">Cook Islands</OPTION>
						<OPTION VALUE="CR">Costa Rica</OPTION>
						<OPTION VALUE="HR">Croatia</OPTION>
						<OPTION VALUE="CU">Cuba</OPTION>
						<OPTION VALUE="CY">Cyprus</OPTION>
						<OPTION VALUE="CZ">Czech Republic</OPTION>
						<OPTION VALUE="CI">Côte d'Ivoire</OPTION>
						<OPTION VALUE="DK">Denmark</OPTION>
						<OPTION VALUE="DJ">Djibouti</OPTION>
						<OPTION VALUE="DM">Dominica</OPTION>
						<OPTION VALUE="DO">Dominican Republic</OPTION>
						<OPTION VALUE="EC">Ecuador</OPTION>
						<OPTION VALUE="EG">Egypt</OPTION>
						<OPTION VALUE="SV">El Salvador</OPTION>
						<OPTION VALUE="GQ">Equatorial Guinea</OPTION>
						<OPTION VALUE="ER">Eritrea</OPTION>
						<OPTION VALUE="EE">Estonia</OPTION>
						<OPTION VALUE="ET">Ethiopia</OPTION>
						<OPTION VALUE="FK">Falkland Islands</OPTION>
						<OPTION VALUE="FO">Faroe Islands</OPTION>
						<OPTION VALUE="FJ">Fiji</OPTION>
						<OPTION VALUE="FI">Finland</OPTION>
						<OPTION VALUE="FR">France</OPTION>
						<OPTION VALUE="GF">French Guiana</OPTION>
						<OPTION VALUE="PF">French Polynesia</OPTION>
						<OPTION VALUE="TF">French Southern Territories</OPTION>
						<OPTION VALUE="GA">Gabon</OPTION>
						<OPTION VALUE="GM">Gambia</OPTION>
						<OPTION VALUE="GE">Georgia</OPTION>
						<OPTION VALUE="DE">Germany</OPTION>
						<OPTION VALUE="GH">Ghana</OPTION>
						<OPTION VALUE="GI">Gibraltar</OPTION>
						<OPTION VALUE="GR">Greece</OPTION>
						<OPTION VALUE="GL">Greenland</OPTION>
						<OPTION VALUE="GD">Grenada</OPTION>
						<OPTION VALUE="GP">Guadeloupe</OPTION>
						<OPTION VALUE="GU">Guam</OPTION>
						<OPTION VALUE="GT">Guatemala</OPTION>
						<OPTION VALUE="GN">Guinea</OPTION>
						<OPTION VALUE="GW">Guinea-Bissau</OPTION>
						<OPTION VALUE="GY">Guyana</OPTION>
						<OPTION VALUE="HT">Haiti</OPTION>
						<OPTION VALUE="HM">Heard Island And McDonald Islands</OPTION>
						<OPTION VALUE="HN">Honduras</OPTION>
						<OPTION VALUE="HK">Hong Kong</OPTION>
						<OPTION VALUE="HU">Hungary</OPTION>
						<OPTION VALUE="IS">Iceland</OPTION>
						<OPTION VALUE="IN">India</OPTION>
						<OPTION VALUE="ID">Indonesia</OPTION>
						<OPTION VALUE="IR">Iran</OPTION>
						<OPTION VALUE="IQ">Iraq</OPTION>
						<OPTION VALUE="IE">Ireland</OPTION>
						<OPTION VALUE="IL">Israel</OPTION>
						<OPTION VALUE="IT">Italy</OPTION>
						<OPTION VALUE="JM">Jamaica</OPTION>
						<OPTION VALUE="JP">Japan</OPTION>
						<OPTION VALUE="JO">Jordan</OPTION>
						<OPTION VALUE="KZ">Kazakhstan</OPTION>
						<OPTION VALUE="KE">Kenya</OPTION>
						<OPTION VALUE="KI">Kiribati</OPTION>
						<OPTION VALUE="KW">Kuwait</OPTION>
						<OPTION VALUE="KG">Kyrgyzstan</OPTION>
						<OPTION VALUE="LA">Laos</OPTION>
						<OPTION VALUE="LV">Latvia</OPTION>
						<OPTION VALUE="LB">Lebanon</OPTION>
						<OPTION VALUE="LS">Lesotho</OPTION>
						<OPTION VALUE="LR">Liberia</OPTION>
						<OPTION VALUE="LY">Libya</OPTION>
						<OPTION VALUE="LI">Liechtenstein</OPTION>
						<OPTION VALUE="LT">Lithuania</OPTION>
						<OPTION VALUE="LU">Luxembourg</OPTION>
						<OPTION VALUE="MO">Macao</OPTION>
						<OPTION VALUE="MK">Macedonia</OPTION>
						<OPTION VALUE="MG">Madagascar</OPTION>
						<OPTION VALUE="MW">Malawi</OPTION>
						<OPTION VALUE="MY">Malaysia</OPTION>
						<OPTION VALUE="MV">Maldives</OPTION>
						<OPTION VALUE="ML">Mali</OPTION>
						<OPTION VALUE="MT">Malta</OPTION>
						<OPTION VALUE="MH">Marshall Islands</OPTION>
						<OPTION VALUE="MQ">Martinique</OPTION>
						<OPTION VALUE="MR">Mauritania</OPTION>
						<OPTION VALUE="MU">Mauritius</OPTION>
						<OPTION VALUE="YT">Mayotte</OPTION>
						<OPTION VALUE="MX">Mexico</OPTION>
						<OPTION VALUE="FM">Micronesia</OPTION>
						<OPTION VALUE="MD">Moldova</OPTION>
						<OPTION VALUE="MC">Monaco</OPTION>
						<OPTION VALUE="MN">Mongolia</OPTION>
						<OPTION VALUE="MS">Montserrat</OPTION>
						<OPTION VALUE="MA">Morocco</OPTION>
						<OPTION VALUE="MZ">Mozambique</OPTION>
						<OPTION VALUE="MM">Myanmar</OPTION>
						<OPTION VALUE="NA">Namibia</OPTION>
						<OPTION VALUE="NR">Nauru</OPTION>
						<OPTION VALUE="NP">Nepal</OPTION>
						<OPTION VALUE="NL">Netherlands</OPTION>
						<OPTION VALUE="AN">Netherlands Antilles</OPTION>
						<OPTION VALUE="NC">New Caledonia</OPTION>
						<OPTION VALUE="NZ">New Zealand</OPTION>
						<OPTION VALUE="NI">Nicaragua</OPTION>
						<OPTION VALUE="NE">Niger</OPTION>
						<OPTION VALUE="NG">Nigeria</OPTION>
						<OPTION VALUE="NU">Niue</OPTION>
						<OPTION VALUE="NF">Norfolk Island</OPTION>
						<OPTION VALUE="KP">North Korea</OPTION>
						<OPTION VALUE="MP">Northern Mariana Islands</OPTION>
						<OPTION VALUE="NO">Norway</OPTION>
						<OPTION VALUE="OM">Oman</OPTION>
						<OPTION VALUE="PK">Pakistan</OPTION>
						<OPTION VALUE="PW">Palau</OPTION>
						<OPTION VALUE="PS">Palestine</OPTION>
						<OPTION VALUE="PA">Panama</OPTION>
						<OPTION VALUE="PG">Papua New Guinea</OPTION>
						<OPTION VALUE="PY">Paraguay</OPTION>
						<OPTION VALUE="PE">Peru</OPTION>
						<OPTION VALUE="PH">Philippines</OPTION>
						<OPTION VALUE="PN">Pitcairn</OPTION>
						<OPTION VALUE="PL">Poland</OPTION>
						<OPTION VALUE="PT">Portugal</OPTION>
						<OPTION VALUE="PR">Puerto Rico</OPTION>
						<OPTION VALUE="QA">Qatar</OPTION>
						<OPTION VALUE="RE">Reunion</OPTION>
						<OPTION VALUE="RO">Romania</OPTION>
						<OPTION VALUE="RU">Russia</OPTION>
						<OPTION VALUE="RW">Rwanda</OPTION>
						<OPTION VALUE="SH">Saint Helena</OPTION>
						<OPTION VALUE="KN">Saint Kitts And Nevis</OPTION>
						<OPTION VALUE="LC">Saint Lucia</OPTION>
						<OPTION VALUE="PM">Saint Pierre And Miquelon</OPTION>
						<OPTION VALUE="VC">Saint Vincent And The Grenadines</OPTION>
						<OPTION VALUE="WS">Samoa</OPTION>
						<OPTION VALUE="SM">San Marino</OPTION>
						<OPTION VALUE="ST">Sao Tome And Principe</OPTION>
						<OPTION VALUE="SA">Saudi Arabia</OPTION>
						<OPTION VALUE="SN">Senegal</OPTION>
						<OPTION VALUE="CS">Serbia and Montenegro</OPTION>
						<OPTION VALUE="SC">Seychelles</OPTION>
						<OPTION VALUE="SL">Sierra Leone</OPTION>
						<OPTION VALUE="SG">Singapore</OPTION>
						<OPTION VALUE="SK">Slovakia</OPTION>
						<OPTION VALUE="SI">Slovenia</OPTION>
						<OPTION VALUE="SB">Solomon Islands</OPTION>
						<OPTION VALUE="SO">Somalia</OPTION>
						<OPTION VALUE="ZA">South Africa</OPTION>
						<OPTION VALUE="GS">South Georgia And The South Sandwich Islands</OPTION>
						<OPTION VALUE="KR">South Korea</OPTION>
						<OPTION VALUE="ES">Spain</OPTION>
						<OPTION VALUE="LK">Sri Lanka</OPTION>
						<OPTION VALUE="SD">Sudan</OPTION>
						<OPTION VALUE="SR">Suriname</OPTION>
						<OPTION VALUE="SJ">Svalbard And Jan Mayen</OPTION>
						<OPTION VALUE="SZ">Swaziland</OPTION>
						<OPTION VALUE="SE">Sweden</OPTION>
						<OPTION VALUE="CH">Switzerland</OPTION>
						<OPTION VALUE="SY">Syria</OPTION>
						<OPTION VALUE="TW">Taiwan</OPTION>
						<OPTION VALUE="TJ">Tajikistan</OPTION>
						<OPTION VALUE="TZ">Tanzania</OPTION>
						<OPTION VALUE="TH">Thailand</OPTION>
						<OPTION VALUE="CD">The Democratic Republic Of Congo</OPTION>
						<OPTION VALUE="TL">Timor-Leste</OPTION>
						<OPTION VALUE="TG">Togo</OPTION>
						<OPTION VALUE="TK">Tokelau</OPTION>
						<OPTION VALUE="TO">Tonga</OPTION>
						<OPTION VALUE="TT">Trinidad and Tobago</OPTION>
						<OPTION VALUE="TN">Tunisia</OPTION>
						<OPTION VALUE="TR">Turkey</OPTION>
						<OPTION VALUE="TM">Turkmenistan</OPTION>
						<OPTION VALUE="TC">Turks And Caicos Islands</OPTION>
						<OPTION VALUE="TV">Tuvalu</OPTION>
						<OPTION VALUE="VI">U.S. Virgin Islands</OPTION>
						<OPTION VALUE="UG">Uganda</OPTION>
						<OPTION VALUE="UA">Ukraine</OPTION>
						<OPTION VALUE="AE">United Arab Emirates</OPTION>
						<OPTION VALUE="GB">United Kingdom</OPTION>
						<OPTION VALUE="US">United States</OPTION>
						<OPTION VALUE="UM">United States Minor Outlying Islands</OPTION>
						<OPTION VALUE="UY">Uruguay</OPTION>
						<OPTION VALUE="UZ">Uzbekistan</OPTION>
						<OPTION VALUE="VU">Vanuatu</OPTION>
						<OPTION VALUE="VA">Vatican</OPTION>
						<OPTION VALUE="VE">Venezuela</OPTION>
						<OPTION VALUE="VN">Vietnam</OPTION>
						<OPTION VALUE="WF">Wallis And Futuna</OPTION>
						<OPTION VALUE="EH">Western Sahara</OPTION>
						<OPTION VALUE="YE">Yemen</OPTION>
						<OPTION VALUE="ZM">Zambia</OPTION>
						<OPTION VALUE="ZW">Zimbabwe</OPTION>
						<OPTION VALUE="AX">Åland Islands</OPTION>
						</select>
				</td>
                <td>Mã vùng ngân hàng phát hành</td>
            </tr>
            <tr>
                <td><strong><em>Display</em></strong></td>
                <td>
                    <select name="display">
                        <option value="" selected="selected">Auto detect</option>
                        <option value="mobile">Mobile</option>
                    </select>
                </td>
                <td>Hiện thị trên thiết bị Desktop hay Mobile</td>
            </tr>
            </tbody>
        </table>
    </center>
</form>
</body>
</html>