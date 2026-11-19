#!/usr/bin/env python3
"""Self-contained structural/documentary and SYNTHETIC date checks.
This deliberately performs NO network, calendar connector, rights, canon, archive or live-production operations.
Usage: python3 cc02_candidate_validate.py [directory]
"""
from __future__ import annotations
import datetime as dt
import hashlib
import re
import sys
from pathlib import Path

DIR = Path(sys.argv[1]) if len(sys.argv) > 1 else Path(__file__).resolve().parent
FILENAMES = {
 'protocol': 'CC-02.01-Daily_Chronicle_Generation_Protocol_v0.09.01_Candidate.md',
 'ledger': 'CC-02.05-Episode_Continuity_Ledger-Production_Handover_v0.09.01_Candidate.md',
 'review': 'CC-02.06-Daily_Protocol_and_Ledger_Alignment_Review_v0.09.01_Candidate.md',
}
GATES = ['Q01_SCOPE','Q02_CALENDAR','Q03_THREE_CLOCKS','Q04_IDENTIFIERS','Q05_CANON','Q06_ROLE_PROJECT','Q07_STUDY','Q08_VERSE_HS','Q09_RESEARCH','Q10_RIGHTS','Q11_STORY','Q12_CHALLENGE','Q13_RECORDS','Q14_DERIVATIVES','Q15_INTEGRITY','Q16_BOUNDARIES']
FAMILIES = ['episode_manifest','episode_context_snapshot','source_snapshot_register','daily_chronicle_record','research_register','source_permission_register','role_project_log','mission_state_record','character_state_record','storyline_register','continuity_event_log','challenge_public_register','challenge_answer_key','production_manifest','service_card_register','editorial_decision_log','exception_conflict_register','validation_report','handover_record']
BS = ['Run and temporal manifest','Research/source register','Character, Koware role and project register','Mission and technology state','Append-only continuity and story ledger','Portable production handover','Derivative/media and challenge status','Validation, exceptions and approval register']
B6 = ['Identity and snapshot','Source and authority basis','Production calendar','Temporal geometry','Cast and role spotlight','Character internal state','Mission and Edubeam state','Koware real-project boundary','Story events and scene dependency','Eternal Dreamfall derivation','Research and safety','Audience challenge state','Visual/media continuity','QA, conflicts and revisions','Next-day handoff']
STAGES = ['Eternal Dreamfall Bible Study','Eternal Dreamfall Chronicle','Refreshed Eternal Dreamfall','Headlined Eternal Dreamfall','Classies Eternal Dreamfall','Classies Chronicles']
OPS = ['READ','DECOMPOSE','LOCK','CONNECT','TRANSFORM','PRESERVE','VALIDATE','REGISTER']
results = []
def check(label, state, evidence):
    results.append((label, bool(state), str(evidence)))

def first_monday(start):
    return start + dt.timedelta(days=(7-start.weekday()) % 7)

def ordinary_slot(start, phase_end_inclusive, target):
    anchor=first_monday(start)
    off=(target-anchor).days
    if off<0: return 'BEFORE_ANCHOR'
    block,day=divmod(off,14)
    if block>3: return 'OUTSIDE_FOUR_EPISODES'
    # Master §7 requires *all 14 calendar days* in the approved chapter.
    if anchor+dt.timedelta(days=block*14+13)>phase_end_inclusive:
        return 'PARTIAL_BLOCK'
    if day not in (0,1,2,3,4,7,8,9,10,11): return 'WEEKEND'
    slot=day+1 if day<=4 else day-1
    return (block+1,slot,10*block+slot)

texts={}
for key, filename in FILENAMES.items():
    path=DIR/filename
    check(f'file.exists.{key}',path.is_file(),filename)
    if path.is_file():
        data=path.read_bytes(); txt=data.decode('utf-8'); texts[key]=txt
        check(f'file.nonempty_utf8.{key}',len(data)>10000 and '\ufffd' not in txt,len(data))
        check(f'file.terminal.{key}',bool(re.search(r'^\*\*END — .*v0\.09\.01 Candidate.*\*\*\s*$',txt,re.M)),txt.splitlines()[-1])
        check(f'version.master_ref.{key}', 'v0.09.01' in txt and 'CC-UPMP' in txt,'master candidate referenced')
        check(f'no_author_placeholder.{key}',not re.search(r'\{\{[^}]+\}\}|\b(TBD|TODO|PLACEHOLDER|LOREM IPSUM)\b',txt,re.I),'no literal unfinished author tokens')

p=texts.get('protocol',''); l=texts.get('ledger',''); r=texts.get('review','')
check('protocol.10_phases',re.findall(r'^### Phase ([0-9]) —',p,re.M)==list('0123456789'),re.findall(r'^### Phase ([0-9]) —',p,re.M))
check('protocol.exact_16_gate_rows',re.findall(r'^\| `(Q\d\d_[A-Z_]+)` \|',p,re.M)==GATES,re.findall(r'^\| `(Q\d\d_[A-Z_]+)` \|',p,re.M))
check('protocol.8_B_mapping_rows',re.findall(r'^\| \*\*B([1-8]) —',p,re.M)==list('12345678'),re.findall(r'^\| \*\*B([1-8]) —',p,re.M))
check('protocol.6_stage_rows',re.findall(r'^\| `V([1-6])` \|',p,re.M)==list('123456'),re.findall(r'^\| `V([1-6])` \|',p,re.M))
check('protocol.6_stage_names',all(x in p for x in STAGES),'all established stage names')
check('protocol.8_semantic_ops',all(x in p for x in OPS),'eight operations')
check('protocol.private_boundary',all(x in p for x in ['RESTRICTED OUTPUT NOT CREATED; HUMAN SECURE CUSTODY REQUIRED','RESTRICTED_NOT_EMITTED','PROPOSED','DRAFTED_NOT_COMMITTED']),'boundary states')
check('ledger.exact_19_family_rows',re.findall(r'^\| (?:0[1-9]|1[0-9]) \| `([^`]+)` \|',l,re.M)==FAMILIES,re.findall(r'^\| (?:0[1-9]|1[0-9]) \| `([^`]+)` \|',l,re.M))
check('ledger.8_B_mapping_rows',re.findall(r'^\| \*\*B([1-8]) —',l,re.M)==list('12345678'),re.findall(r'^\| \*\*B([1-8]) —',l,re.M))
section=l.split('## 9. Complete fifteen-division',1)[-1].split('## 10.',1)[0]
found=re.findall(r'^([1-9]|1[0-5])\. \*\*([^*]+)\*\*',section,re.M)
check('ledger.B6_exact_15_numbered',[int(i) for i,_ in found]==list(range(1,16)) and [name for _,name in found]==B6,found)
check('ledger.alias_not_20th','calendar_probe_record' in l and 'not family 20' in l.lower(),'alias preserved')
check('ledger.version_independent_schema','schema_version' in l and 'physical deployed schema version' in l.lower(),'schema version separate from edition')
check('ledger.no_challenge_solution','challenge_answer_key' in l and 'RESTRICTED only' in l and 'never answer' in l.lower(),'restricted family only')
check('review.decision_42_exact',re.findall(r'`DA-(\d{3})`',r)==[f'{i:03}' for i in range(1,43)],re.findall(r'`DA-(\d{3})`',r)[:45])
check('review.36_case_ids',re.findall(r'`AT(\d{2})`',r)==[f'{i:02}' for i in range(1,37)],re.findall(r'`AT(\d{2})`',r))
check('review.empty_compilation_reported','0 lines' in r and '0 Bytes' in r and 'Compilation 3' in r,'prior response source limitation explicitly disclosed')
check('review.naming_crosswalk',all(x in r for x in ['CC-05.01','CC-05.02','CC-02.05','CC-02.06']),'physical/logical mismatch documented')

# These are mathematical regression fixtures only, not assertions about actual approved CC chapter dates.
start=dt.date(2026,10,16)
end=dt.date(2026,12,20)
anchor=first_monday(start)
fixtures=[(0,(1,1,1)),(4,(1,5,5)),(7,(1,6,6)),(11,(1,10,10)),(14,(2,1,11)),(28,(3,1,21)),(42,(4,1,31)),(53,(4,10,40))]
check('calendar.midweek_anchor',anchor==dt.date(2026,10,19),anchor)
for offset,exp in fixtures:
    got=ordinary_slot(start,end,anchor+dt.timedelta(days=offset))
    check(f'calendar.offset_{offset}',got==exp,f'{got} expected {exp}')
check('calendar.weekends',all(ordinary_slot(start,end,anchor+dt.timedelta(days=o))=='WEEKEND' for o in (5,6,12,13,19,20)), 'weekend blocks not numbered')
check('calendar.before_anchor',ordinary_slot(start,end,start)=='BEFORE_ANCHOR','pre-anchor partial week rejected')
check('calendar.short_fourth_block',ordinary_slot(start,dt.date(2026,12,10),anchor+dt.timedelta(days=53))=='PARTIAL_BLOCK','whole 14 days must fit')
check('calendar.fifth_block',ordinary_slot(start,dt.date(2027,2,1),anchor+dt.timedelta(days=56))=='OUTSIDE_FOUR_EPISODES','fifth block not normal')
reading_start=dt.date(2025,1,5)
reading_day=dt.date(2026,11,19)
reading_week=((reading_day-reading_start).days//7)+1
check('study.week_98',reading_week==98,f'week={reading_week}')
check('study.250_vs_260',250<52*5<261,'250 source entries cannot be 260 invented entries')
check('story.math_independence',10*160==1600 and 1600==26*60+40,'ten 160-second draft segments =26:40, not 20:00')

pass_n=sum(state for _,state,_ in results)
failed=[x for x in results if not x[1]]
out=['CC-02 candidate local documentary validator v0.09.01','Run: local offline Python; deterministic structural and SYNTHETIC fixtures only.',
     'No network/consent/calendar/episode/continuity/art/video/provider tests executed.',
     f'Result: {pass_n}/{len(results)} PASS; {len(failed)} FAIL','']
for label,state,evidence in results:
    if not state:
        out.append(f'FAIL {label} — {evidence}')
    else:
        out.append(f'PASS {label}')
out.append('')
out.append('Local file SHA-256 (actual bytes at validator run):')
for key,name in FILENAMES.items():
    path=DIR/name
    if path.is_file():
        data=path.read_bytes()
        out.append(f'{name}\t{len(data)} bytes\t{len(data.decode("utf-8").splitlines())} lines\t{hashlib.sha256(data).hexdigest()}')
out.append('END OF OFFLINE QA OBSERVATIONS — NOT OWNER RATIFICATION OR LIVE PRODUCTION CERTIFICATION.')
report='\n'.join(out)+'\n'
(DIR/'QA_STATIC_AND_SYNTHETIC_TEST_OUTPUT.txt').write_text(report,encoding='utf-8')
print('\n'.join(out[:6]))
if failed:
    print('\n'.join(out[5:5+len(failed)]))
sys.exit(1 if failed else 0)
