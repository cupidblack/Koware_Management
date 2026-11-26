/* HopefulWay Oxford House — Apps Script v1.0.0. Standalone project.
 * Run createHopefulWayIntake once. Form remains unpublished. See deployment guide.
 */
const HW = Object.freeze({
  alert: 'support@buzzjuice.net', zone: 'Africa/Accra',
  formTitle: 'HopefulWay Oxford House — Interview Waiting List & Interest Registration',
  statuses: ['New interest','Consent verified','Contacted','Information given','Preliminary booked','Preliminary completed','House interview booked','House decision pending','Accepted','Move-in scheduled','Moved in','Future interest','Deferred','Not accepted','Withdrawn','Closed'],
  admin: ['Reference','Form response ID','Received','Status','Consent verified on','Sponsor verification','Assigned owner','Next action','Next action date','Last contact','Preliminary completed on','House interview on','Vote outcome','Move-in on','Retention review on','Alert sent on'],
  questions: ['Adult eligibility','Applicant permission','Submission route','Supervisor or referrer name','Institution / organisation','Supervisor contact','Applicant first name','Applicant last name','Safe contact channel','Safe phone or email / authorised relay','Safe contact time','Current town or area','House interest','Preferred location','Consider Ashaiman if suitable','Transition timing','Expected discharge or earliest move-in date','Source','Sponsor arrangement','Permission to contact sponsor','House conditions acknowledgement','Communication support','Future contact permission']
});
function createHopefulWayIntake() {
  const p=PropertiesService.getScriptProperties();
  if (p.getProperty('FORM_ID') || p.getProperty('BUILD_STARTED')) throw new Error('Build exists or was interrupted. Inspect execution and Drive before rebuilding.');
  p.setProperty('BUILD_STARTED',new Date().toISOString());
  const f=FormApp.create(HW.formTitle,false);
  p.setProperty('FORM_ID',f.getId());
  f.setDescription('Voluntary interest in peer-supported sober living, not treatment or a bed reservation. Current focus: Ashaiman men’s house, subject to confirmation. Other locations and separate women’s housing are future interest only. You must be 18 or older and agree personally to this enquiry. If you do not agree, close this form without submitting. If you are in treatment, move-in is only after appropriate discharge planning. House interviews and a resident vote are required. Ask for confirmed costs, availability and rules before committing.\n\nPrivacy: HopefulWay Foundation uses the information to respond to this request and coordinate steps you choose. Named authorised intake personnel may access it; approved Google and technical service providers support the process. Contact your supervisor or sponsor only with your permission. Closed/unresponsive enquiries are proposed for review and deletion after 90 days of last meaningful contact; future-interest permission is reviewed after 180 days. Ask for access, correction or withdrawal via info@hopefulwayfoundation.org. Do not enter diagnoses, medical records, ID numbers or detailed treatment information. Ghana privacy approval is required before publication.\n\nLearn more: https://hopefulwayfoundation.org/ and https://www.oxfordhouse.org/ ; Ghana video: https://youtu.be/OH2X5Ib8eIc .');
  f.setConfirmationMessage('Thank you. Your voluntary interest has been received. An authorised team member will contact you using the method you selected. Registration does not guarantee admission or accommodation. Contact info@hopefulwayfoundation.org if you wish to withdraw or correct details.');
  f.setCollectEmail(false).setAllowResponseEdits(false).setPublishingSummary(false).setShowLinkToRespondAgain(false).setProgressBar(true);
  const mc=(t,choices,req=true,help='')=>{let q=f.addMultipleChoiceItem().setTitle(t).setChoiceValues(choices).setRequired(req); if(help)q.setHelpText(help);return q;};
  const tx=(t,req=true,help='')=>{let q=f.addTextItem().setTitle(t).setRequired(req);if(help)q.setHelpText(help);return q;};
  const chk=(t,v)=>f.addCheckboxItem().setTitle(t).setChoiceValues([v]).setRequired(true);
  chk('Adult eligibility','I am 18 or older, or I am assisting an adult aged 18 or older who has personally agreed.');
  chk('Applicant permission','I personally agree to the collection and use of this information for this voluntary enquiry, or the adult applicant has expressly authorised me to submit it after reading/hearing the privacy notice. I understand that this is not admission and consent may be withdrawn.');
  const route=f.addMultipleChoiceItem().setTitle('Submission route').setRequired(true);
  const assist=f.addPageBreakItem().setTitle('Authorised assisted referral');
  tx('Supervisor or referrer name',true,'Only if the applicant agrees to your involvement.');
  tx('Institution / organisation',false,'Leave blank for family or independent assistance.');
  tx('Supervisor contact',true,'Work phone or email; applicant authorises this contact for coordination.');
  const common=f.addPageBreakItem().setTitle('Applicant contact and housing interests');
  route.setChoices([route.createChoice('I am the applicant',common),route.createChoice('I am assisting with express applicant permission',assist)]);
  tx('Applicant first name'); tx('Applicant last name');
  mc('Safe contact channel',['Phone call','WhatsApp','Email','Authorised supervisor relay']);
  tx('Safe phone or email / authorised relay',true,'Provide only a safe contact the applicant authorises. No medical details.');
  tx('Safe contact time',true,'For example weekday afternoons; do not include private information.');
  tx('Current town or area',true,'Town/area only, no street address.');
  f.addListItem().setTitle('House interest').setChoiceValues(['Ashaiman men’s house — subject to availability','Future men’s house / location interest only','Future women’s house / location interest only','Information only']).setRequired(true);
  tx('Preferred location',false,'Type a preferred town or neighbourhood. No future house is guaranteed.');
  mc('Consider Ashaiman if suitable',['Yes','No','Need more information','Not applicable']);
  mc('Transition timing',['In treatment / planning discharge','Treatment completed','Community / self-referral','Exploring options only','Prefer to discuss privately']);
  f.addDateItem().setTitle('Expected discharge or earliest move-in date').setRequired(false).setIncludesYear(true);
  mc('Source',['Self','Rehabilitation facility','Health service','Family or sponsor','Recovery group','Educational institution','Social service / NGO','Other']);
  mc('Sponsor arrangement',['Self-funded / no sponsor needed','Sponsor agrees to referral and expected funding discussion','Sponsor agreement pending — information only for now','Would like to discuss support','Prefer to discuss privately'],true,'An adult applicant’s consent is primary. No sponsor is required for independent self-referral. A pending sponsor decision is not a confirmed funded referral.');
  mc('Permission to contact sponsor',['Yes — ask me for details privately','No','Not applicable / undecided']);
  chk('House conditions acknowledgement','I understand that the residence is substance-free, shared expenses and house rules apply, and a house interview and resident vote are required. I will receive the actual rules and costs before any commitment.');
  tx('Communication support',false,'Optional communication or accessibility preference only; no diagnosis or medical information.');
  mc('Future contact permission',['Yes, contact me about future suitable housing options','No, only respond to this enquiry']);
  const s=SpreadsheetApp.create('HWF Oxford House — RESTRICTED applicant register');
  p.setProperty('SHEET_ID',s.getId()); s.setSpreadsheetTimeZone(HW.zone);
  const a=s.getSheets()[0].setName('Applicants');
  header_(a,HW.admin.concat(HW.questions));
  a.getRange(2,4,a.getMaxRows()-1,1).setDataValidation(SpreadsheetApp.newDataValidation().requireValueInList(HW.statuses,true).setAllowInvalid(false).build());
  a.getRange('E:E').setNumberFormat('yyyy-mm-dd'); a.getRange('I:L').setNumberFormat('yyyy-mm-dd'); a.getRange('N:P').setNumberFormat('yyyy-mm-dd hh:mm');
  header_(s.insertSheet('Deletion audit'),['Reference','Deletion date','Authorised operator','Reason category']);
  const dash=s.insertSheet('Dashboard'); header_(dash,['Current pipeline snapshot','Count']);
  dash.getRange(2,1,HW.statuses.length,2).setValues(HW.statuses.map((x,i)=>[x,'=COUNTIF(Applicants!D2:D,A'+(i+2)+')']));
  dash.getRange(19,1,5,2).setValues([['Total retained enquiries','=COUNTA(Applicants!A2:A)'],['Consent verified records','=COUNT(Applicants!E2:E)'],['Alerts pending','=COUNTIFS(Applicants!A2:A,"<>",Applicants!P2:P,"")'],['Next action due','=COUNTIFS(Applicants!I2:I,">0",Applicants!I2:I,"<="&TODAY())'],['Retention review due','=COUNTIFS(Applicants!O2:O,">0",Applicants!O2:O,"<="&TODAY())']]);
  const inst=SpreadsheetApp.create('HWF Oxford House — institution outreach register');p.setProperty('INSTITUTION_ID',inst.getId());inst.setSpreadsheetTimeZone(HW.zone);
  const it=inst.getSheets()[0].setName('Institutions');header_(it,['Institution ID','Institution','Type','Area','Professional contact','Role','Business phone','Business email','Official source URL','Verified date','Contact permission','Owner','First contact','Last contact','Status','Next action','Next action date','Presentation requested date','Presentation held date','Aggregate referrals','Opt out','Notes (institution only)']);
  it.getRange(2,15,it.getMaxRows()-1,1).setDataValidation(SpreadsheetApp.newDataValidation().requireValueInList(['New','Permission requested','Engaged','Presentation pending','Active partner','Nurture','Closed','Opt out'],true).setAllowInvalid(false).build());
  ScriptApp.newTrigger('handleHopefulWaySubmission').forForm(f).onFormSubmit().create();
  ScriptApp.newTrigger('reconcileHopefulWayIntake').timeBased().everyHours(1).create();
  p.setProperty('BUILD_COMPLETE','yes');
  showHopefulWayLinks();
}
function header_(sheet,values){if(sheet.getMaxColumns()<values.length)sheet.insertColumnsAfter(sheet.getMaxColumns(),values.length-sheet.getMaxColumns());sheet.getRange(1,1,1,values.length).setValues([values]).setFontWeight('bold').setBackground('#dcece5').setWrap(true);sheet.setFrozenRows(1);sheet.getRange(1,1,sheet.getMaxRows(),values.length).createFilter();}
function getHW_(){const p=PropertiesService.getScriptProperties();if(p.getProperty('BUILD_COMPLETE')!=='yes')throw new Error('Incomplete build.');return {f:FormApp.openById(p.getProperty('FORM_ID')),s:SpreadsheetApp.openById(p.getProperty('SHEET_ID')),i:SpreadsheetApp.openById(p.getProperty('INSTITUTION_ID'))};}
function showHopefulWayLinks(){const o=getHW_();console.log(JSON.stringify({formEdit:o.f.getEditUrl(),responder:o.f.getPublishedUrl(),applicantRegister:o.s.getUrl(),institutionRegister:o.i.getUrl()}));}
function handleHopefulWaySubmission(e){if(!e||!e.response)throw new Error('Use the installed form trigger.');withLock_(()=>{const o=getHW_();upsert_(o.s.getSheetByName('Applicants'),e.response);notify_(o.s.getSheetByName('Applicants'));});}
function reconcileHopefulWayIntake(){withLock_(()=>{const o=getHW_(),a=o.s.getSheetByName('Applicants');const ids=new Set(readIds_(a));o.f.getResponses().forEach(r=>{if(!ids.has(r.getId())){upsert_(a,r);ids.add(r.getId());}});notify_(a);});}
function withLock_(fn){const l=LockService.getScriptLock();l.waitLock(30000);try{return fn();}finally{l.releaseLock();}}
function readIds_(a){return a.getLastRow()>1?a.getRange(2,2,a.getLastRow()-1,1).getValues().map(x=>x[0]):[];}
function safe_(v){if(v==null)return '';if(v instanceof Date)return v;if(Array.isArray(v))v=v.join('; ');return "'"+String(v);}
function reference_(r){const hash=Utilities.computeDigest(Utilities.DigestAlgorithm.SHA_256,r.getId()).map(b=>('0'+(b&255).toString(16)).slice(-2)).join('').slice(0,16).toUpperCase();return 'HWF-OH-'+Utilities.formatDate(r.getTimestamp(),HW.zone,'yyyy')+'-'+hash;}
function upsert_(a,r){if(readIds_(a).includes(r.getId()))return;const ref=reference_(r),vals={};r.getItemResponses().forEach(x=>vals[x.getItem().getTitle()]=x.getResponse());const due=new Date(r.getTimestamp().getTime()+86400000);const review=new Date(r.getTimestamp().getTime()+90*86400000);const admin=[ref,r.getId(),r.getTimestamp(),'New interest','','Not checked','','Verify consent and safe contact',due,'','','','','',review,''];a.appendRow(admin.concat(HW.questions.map(q=>safe_(vals[q]))));}
function notify_(a){if(a.getLastRow()<2)return;const rows=a.getRange(2,1,a.getLastRow()-1,HW.admin.length).getValues();for(let i=0;i<rows.length;i++){const r=rows[i];if(!r[0]||r[15])continue;if(MailApp.getRemainingDailyQuota()<1)return;try{MailApp.sendEmail(HW.alert,'HopefulWay intake: new enquiry '+r[0],'A new enquiry was received. Reference: '+r[0]+'. Authorised intake personnel should check the restricted register. Do not reply with applicant details.');a.getRange(i+2,16).setValue(new Date());}catch(err){console.error('Notification failed; retry on reconciliation. '+String(err).slice(0,100));return;}}}
/* Change FORM publication/acceptance from the Google Forms UI after approval.
 * Controlled deletion: set DELETE_REFERENCE and DELETE_REASON in Script Properties,
 * run deleteApprovedHopefulWayRecord, then remove properties (script also clears).
 * Operator must verify authority, retention exceptions, exports and any backup copies.
 */
function deleteApprovedHopefulWayRecord(){const p=PropertiesService.getScriptProperties(),ref=p.getProperty('DELETE_REFERENCE'),reason=p.getProperty('DELETE_REASON');if(!/^HWF-OH-\d{4}-[A-F0-9]{16}$/.test(ref||'')||!['Withdrawal','Retention expiry','Synthetic test','Correction replacement'].includes(reason))throw new Error('Set a valid reference and approved reason category.');withLock_(()=>{const o=getHW_(),a=o.s.getSheetByName('Applicants');if(a.getLastRow()<2)throw new Error('No record.');const rows=a.getRange(2,1,a.getLastRow()-1,2).getValues(),ix=rows.findIndex(r=>r[0]===ref);if(ix<0)throw new Error('Reference not found.');const rid=rows[ix][1];if(o.f.getResponses().some(r=>r.getId()===rid))o.f.deleteResponse(rid);a.deleteRow(ix+2);o.s.getSheetByName('Deletion audit').appendRow([ref,new Date(),Session.getEffectiveUser().getEmail()||'Authorised script operator',reason]);p.deleteProperty('DELETE_REFERENCE');p.deleteProperty('DELETE_REASON');});}
