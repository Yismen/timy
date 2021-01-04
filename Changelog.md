# CHANGELOG
### 3.2.2:
- Remove package legacy factories.
- Adopt laravel 8 factories style.

### 3.2.1:
- Notify about active sessions passing the hours threshold.
- Put tests into the correct folder.
- Add Changelog.md File.

### 3.2.0:
- Add abitlity to delete teams.
- Prompt first.
- Remove all users under team.
- Delete Team.
- Move Create Team to a separate component.
- Separate Update and Delete Components.
- Refactor Teams Table after components were set as child.
- Fix Issue #1: Calling getData from the mount method was causing reactivity issues and relationships break in the teams collection.