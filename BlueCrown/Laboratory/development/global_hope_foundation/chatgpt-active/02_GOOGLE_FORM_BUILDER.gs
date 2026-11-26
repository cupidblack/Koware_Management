/**
 * Hopeful Way Foundation — Oxford House expression-of-interest intake
 * Release 1.00, 26 November 2026.
 *
 * RUN ONCE: createHopefulWayIntake() in a Hopeful Way-authorised Google Apps
 * Script account. The created form is CLOSED until the designated operator
 * completes the privacy/charter/cost/house-rules approval and publishes it.
 *
 * This is intentionally NOT a clinical history form, an admissions decision,
 * a sponsor permission form, or a guarantee of accommodation.
 *
 * External publication check: update/approve the privacy notice and verify
 * all house rules and data-processor obligations BEFORE opening the form.
 */

const HWF = Object.freeze({
  FORM_PROP: 'HWF_OXFORD_INTEREST_FORM_ID_V1',
  SHEET_PROP: 'HWF_OXFORD_INTEREST_SHEET_ID_V1',
  NOTIFICATION_EMAIL: 'support@buzzjuice.net',
  TIME_ZONE: 'Africa/Accra',
  FORM_TITLE: 'Hopeful Way Oxford House — Confidential Expression of Interest',
  SHEET_TITLE: 'Hopeful Way Oxford House — Restricted Referral Register',
  STATUS_VALUES: [
    'NEW', 'CONSENT-PENDING', 'CONTACTED', 'INFORMATION-SENT',
    'VIDEO-INTERVIEW-SCHEDULED', 'VIDEO-INTERVIEW-COMPLETE',
    'HOUSE-INTERVIEW-REQUESTED', 'HOUSE-INTERVIEW-COMPLETE',
    'MEMBER-VOTE-PENDING', 'ACCEPTED-PENDING-MOVE-IN', 'MOVED-IN',
    'DEFERRED', 'NOT-ACCEPTED', 'WITHDRAWN'
  ]
});

function createHopefulWayIntake() {
  const props = PropertiesService.getScriptProperties();
  const existing = props.getProperty(HWF.FORM_PROP);
  if (existing) {
    const oldForm = FormApp.openById(existing);
    const oldBook = SpreadsheetApp.openById(props.getProperty(HWF.SHEET_PROP));
    console.log('Existing form found; no duplicates created.');
    logLinks_(oldForm, oldBook);
    return;
  }

  // This form is deliberately closed immediately on creation.
  const form = FormApp.create(HWF.FORM_TITLE);
  form.setAcceptingResponses(false);
  form.setCollectEmail(false);
  form.setLimitOneResponsePerUser(false);
  form.setProgressBar(true);
  form.setConfirmationMessage(
    'Thank you. Your expression of interest has been received. A designated ' +
    'member of the Hopeful Way team will review it and seek to confirm the ' +
    'interested adult’s consent. Submitting does not guarantee a house place or ' +
    'admission. To correct or withdraw an entry, contact support@buzzjuice.net. ' +
    'Please do not email medical or treatment records.'
  );
  form.setDescription(
    'For adults considering peer-run, alcohol- and drug-free recovery housing. ' +
    'The only existing house identified for this pilot is the Ashiaman men’s ' +
    'residence; other locations and women’s housing are future-interest ' +
    'enquiries only and are not currently offered placements. A residence is ' +
    'not a detox or treatment centre. The house’s residents interview applicants ' +
    'and decide admission; no placement or bed is guaranteed.\n\n' +
    'PRIVACY NOTICE — FOR WRITTEN APPROVAL BEFORE OPENING THE FORM: ' +
    'Hopeful Way Foundation receives only the details on this form so a small ' +
    'authorised team can reply about the Oxford House information and voluntary ' +
    'interview process. The Foundation determines the purpose of this intake; ' +
    'Google provides the form and restricted storage. The account owner must ' +
    'confirm Ghana Data Protection Commission requirements, cloud/transfer ' +
    'arrangements and legal basis before use. This form does not request medical ' +
    'records or treatment history. In the proposed pilot policy, interest ' +
    'records that do not lead to admission are reviewed for secure deletion ' +
    '90 days after the last meaningful contact, subject to applicable laws and ' +
    'the Foundation’s approved schedule. Contact support@buzzjuice.net to ask ' +
    'for access, correction, withdrawal or deletion. Do not include private ' +
    'medical information in free-text fields. The team will contact applicants ' +
    'only by a method they agree is safe.'
  );

  section_(form, '1. Consent and who is submitting',
           'This is an interest list, not a clinical application or guaranteed placement.');
  choice_(form, 'Who is completing this form?', [
    'I am the adult interested in residence',
    'I am a supervisor assisting an adult who requested this',
    'I am another person assisting an adult who requested this'
  ], true);
  choice_(form, 'Please confirm the person interested in housing is aged 18 or older.', [
    'Yes — the interested person is 18 or older'
  ], true);
  choice_(form,
    'Has the interested adult freely agreed that Hopeful Way may record these details and contact them or their authorised helper about this housing enquiry?',
    ['Yes — their agreement has been obtained and may be confirmed directly'], true);
  choice_(form,
    'Does the interested adult understand that they can decline, correct details or withdraw without affecting treatment or services?',
    ['Yes'], true);

  section_(form, '2. Interested adult',
           'Only enough information to find and contact the interested person.');
  text_(form, 'First name of the interested adult', true);
  text_(form, 'Last name of the interested adult', true);
  choice_(form, 'Which housing information is being requested?', [
    'Current Ashiaman men’s residence — request for consideration',
    'Future separate women’s house — interest only, no open house promised',
    'Other preferred area — future interest only, no open house promised',
    'General Oxford House information; not seeking a place now'
  ], true);
  text_(form, 'Preferred town or area (free text; not a promise of availability)', false);
  choice_(form, 'Possible time to move or receive more information', [
    'Within the next 30 days', 'In 1 to 3 months', 'Later than 3 months', 'Not sure'
  ], true);

  section_(form, '3. Safe communication and referral source',
           'If direct contact is unsafe, share only an agreed supervisor contact.');
  choice_(form, 'Which communication method has the adult approved?', [
    'Call the interested adult directly',
    'WhatsApp the interested adult directly',
    'Email the interested adult directly',
    'Contact the authorised supervisor/helper first'
  ], true);
  text_(form, 'Approved telephone, WhatsApp or email contact for first reply', true);
  text_(form, 'Referring organisation or facility (if relevant)', false);
  text_(form, 'Name of supervisor or helper (if someone is helping)', false);
  choice_(form, 'Has a sponsor/support person been identified?', [
    'Yes', 'No', 'Not applicable', 'Prefer to discuss privately'
  ], false);
  form.addSectionHeaderItem().setTitle('Do not share sponsor details now')
    .setHelpText('Any sponsor role and any contribution to costs will be discussed later only with the adult’s permission. Sponsor permission does not replace the adult’s own consent.');

  section_(form, '4. Understanding and final acknowledgement',
           'You will receive the current approved residence costs and bylaws before the house interview.');
  choice_(form, 'Does the interested adult know this is substance-free, shared, self-run recovery housing and NOT detox or clinical treatment?', ['Yes'], true);
  choice_(form, 'Does the interested adult understand that the residents interview and vote on admission, that house rules/cost sharing apply, and that completing this form does not guarantee a bed?', ['Yes'], true);
  choice_(form,
    'Has the interested adult been shown the privacy notice above, or has it been explained before this form was submitted?',
    ['Yes — the notice was read/explained and the enquiry is voluntary'], true);

  const book = SpreadsheetApp.create(HWF.SHEET_TITLE);
  form.setDestination(FormApp.DestinationType.SPREADSHEET, book.getId());
  addRegisterTabs_(book);
  props.setProperty(HWF.FORM_PROP, form.getId());
  props.setProperty(HWF.SHEET_PROP, book.getId());

  // Single on-submit trigger. Messages contain NO applicant names or answers.
  const exists = ScriptApp.getProjectTriggers().some(function(trigger) {
    return trigger.getHandlerFunction() === 'onHopefulWayInterest';
  });
  if (!exists) {
    ScriptApp.newTrigger('onHopefulWayInterest').forForm(form).onFormSubmit().create();
  }
  console.log('Created CLOSED form. Approve the privacy notice and open manually after QA.');
  logLinks_(form, book);
}

function section_(form, title, text) {
  form.addSectionHeaderItem().setTitle(title).setHelpText(text);
}
function choice_(form, title, choices, required) {
  form.addMultipleChoiceItem().setTitle(title).setChoiceValues(choices).setRequired(required);
}
function text_(form, title, required) {
  form.addTextItem().setTitle(title).setRequired(required);
}

function addRegisterTabs_(book) {
  const pipeline = book.getSheets()[0];
  pipeline.setName('Pipeline (internal statuses)');
  pipeline.appendRow([
    'Case reference', 'Submission timestamp (Ghana)', 'Form response ID', 'Stage',
    'Assigned officer', 'Consent verified personally?', 'Last contact', 'Next action date',
    'Video interview date', 'House interview date', 'Member vote result',
    'Move-in date', 'Withdrawal/deletion request?', 'Private case note reference'
  ]);
  pipeline.setFrozenRows(1);

  const inst = book.insertSheet('Institutions (no clients)');
  inst.appendRow([
    'Institution', 'Public town/area', 'Official contact channel', 'Referrer role/name if confirmed',
    'Contact permission basis', 'First contact date', 'Response status', 'Last contact date',
    'Next action', 'Presentation status', 'Do not contact?', 'Relationship owner'
  ]);
  inst.setFrozenRows(1);

  const houses = book.insertSheet('Housing (confirm live facts)');
  houses.appendRow([
    'House/location', 'Accommodation scope', 'Reported capacity', 'Reported occupancy',
    'Figures confirmed on', 'Safe vacancies confirmed by house', 'Rent/expense share approved?',
    'Charter statement approved?', 'Housing status', 'Lease review'
  ]);
  houses.appendRow([
    'Ashiaman, Greater Accra', 'Men’s residence', 8, 2,
    'NOT RECONFIRMED — source proposal 2026-11-26', '', '', '',
    'RECONFIRM BEFORE PUBLICATION', 'Reported expiry 2027-01-31'
  ]);
  houses.setFrozenRows(1);

  const audit = book.insertSheet('Read me — governance');
  const instructions = [
    ['Purpose','Only authorised Hopeful Way staff should open the response store; pipeline references must not be publicly shared.'],
    ['Consent','Third-party submission is not completed consent verification; confirm directly with the interested adult.'],
    ['Google Form','The Form itself stores responses in addition to the linked Sheet. Withdrawal/deletion must address both.'],
    ['Email alerts','Only a generic new-intake alert is sent to support@buzzjuice.net; no applicant details.'],
    ['Privacy','Review Ghana Data Protection Act 843, controller registration, processor terms and retention before opening the form.'],
    ['Status','This workbook is for intake coordination, NOT for medication, diagnosis or clinical case notes.'],
    ['Publication','Do not advertise the public route until the Form is published/open and both redirects are tested.'],
    ['Availability','Reported capacity and occupancy are unverified planning values; house members must confirm actual figures.'],
    ['No admission promise','Form submission cannot allocate a bed; the house interviews and votes under applicable rules.']
  ];
  audit.getRange(1,1,instructions.length,2).setValues(instructions);
  audit.setColumnWidth(1,180);
  audit.setColumnWidth(2,640);
}

/** Installable Form on-submit handler. Avoids copying responses into email. */
function onHopefulWayInterest(e) {
  if (!e || !e.response) { throw new Error('Use a real Form on-submit event.'); }
  const id = PropertiesService.getScriptProperties().getProperty(HWF.SHEET_PROP);
  if (!id) { throw new Error('Intake workbook not configured.'); }
  const ss = SpreadsheetApp.openById(id);
  const tab = ss.getSheetByName('Pipeline (internal statuses)');
  const lock = LockService.getScriptLock();
  lock.waitLock(30000);
  let ref;
  try {
    const responseId = e.response.getId();
    // Keep idempotent if a submit event is accidentally reprocessed.
    const last = tab.getLastRow();
    const prior = last > 1 ? tab.getRange(2, 3, last - 1, 1).getValues().flat() : [];
    const at = prior.indexOf(responseId);
    if (at >= 0) {
      console.log('Duplicate event ignored: ' + responseId);
      return;
    }
    const serial = String(last).padStart(4, '0');
    ref = 'HWF-OH-' + Utilities.formatDate(new Date(), HWF.TIME_ZONE, 'yyyy') + '-' + serial;
    tab.appendRow([
      ref, Utilities.formatDate(new Date(), HWF.TIME_ZONE, 'yyyy-MM-dd HH:mm:ss'),
      responseId, 'CONSENT-PENDING', '', 'NO — verify directly', '', '', '', '', '', '', '', ''
    ]);
  } finally {
    lock.releaseLock();
  }
  const subject = 'Hopeful Way Oxford House: new restricted interest submission';
  const body = 'A new expression of interest was received.\n' +
    'Case reference: ' + ref + '\n' +
    'Review the restricted Google Sheet using your authorised Google account.\n' +
    'No client details are included in this email.\n' +
    'Confirm the adult’s consent before progressing the referral.\n' +
    'To stop alerts, remove this project’s on-form-submit trigger.';
  MailApp.sendEmail(HWF.NOTIFICATION_EMAIL, subject, body);
}

function showHopefulWayLinks() {
  const p = PropertiesService.getScriptProperties();
  const formId = p.getProperty(HWF.FORM_PROP);
  const sheetId = p.getProperty(HWF.SHEET_PROP);
  if (!formId || !sheetId) { throw new Error('Run createHopefulWayIntake first.'); }
  logLinks_(FormApp.openById(formId), SpreadsheetApp.openById(sheetId));
}
function logLinks_(form, sheet) {
  console.log('FORM EDIT URL: ' + form.getEditUrl());
  console.log('FORM PUBLIC RESPONDENT URL: ' + form.getPublishedUrl());
  console.log('RESTRICTED GOOGLE SHEET: ' + sheet.getUrl());
  console.log('INTAKE FORM CURRENTLY ACCEPTING RESPONSES: ' + form.isAcceptingResponses());
}
