﻿$(document).ready(function(){gp.Util.SetCookie("ContinentDomain","",-1);$("#doNotAccessUSwebzenGNB").click(function(){gp.Util.SetCookie("ContinentDomain","",365);var _arrPath,_path;var strLink="",strPathReturnUrl="";if(parent.location.href.indexOf("us."+gp.strTopDomain)>=0){strLink="http://"+gp.strHostPrefix+"www."+gp.strTopDomain+""}if(parent.location.href.indexOf(parent.location.protocol+"//"+gp.strHostPrefix+"usmember.")>=0){strLink=parent.location.protocol+"//"+gp.strHostPrefix+"member."+gp.strTopDomain+""}if(parent.location.href.indexOf(parent.location.protocol+"//"+gp.strHostPrefix+"uslogin.")>=0){strLink=parent.location.protocol+"//"+gp.strHostPrefix+"login."+gp.strTopDomain+""}if(parent.location.href.indexOf("us.archlordx."+gp.strTopDomain)>=0){strLink="http://"+gp.strHostPrefix+"archlordx."+gp.strTopDomain+""}if(strLink.length<=0){strLink=parent.location.protocol+"//"+parent.location.host}strLink=strLink+parent.location.pathname+parent.location.search;parent.location.href=strLink});$("#doNotAccessUSwebzen").click(function(){gp.Util.SetCookie("ContinentDomain","",365);gp.GNB.CloseLayer()});$("#accessUSwebzen").click(function(){gp.Util.SetCookie("ContinentDomain","us",365);var _arrPath,_path;var strLink="",strPathReturnUrl="";if(parent.location.href.indexOf("www."+gp.strTopDomain)>=0){strLink="http://"+gp.strHostPrefix+"us."+gp.strTopDomain+""}if(parent.location.href.indexOf(parent.location.protocol+"//"+gp.strHostPrefix+"member.")>=0){strLink=parent.location.protocol+"//"+gp.strHostPrefix+"usmember."+gp.strTopDomain+""}if(parent.location.href.indexOf(parent.location.protocol+"//"+gp.strHostPrefix+"login.")>=0){strLink=parent.location.protocol+"//"+gp.strHostPrefix+"uslogin."+gp.strTopDomain+""}if(parent.location.href.indexOf("archlordx."+gp.strTopDomain)>=0){strLink="http://"+gp.strHostPrefix+"us.archlordx."+gp.strTopDomain+""}if(strLink.length<=0){strLink=parent.location.protocol+"//"+parent.location.host}strLink=strLink+parent.location.pathname+parent.location.search;parent.location.href=strLink})});