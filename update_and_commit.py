import subprocess, os, zipfile

# 1. Update deploy.zip
print('Updating deploy.zip...')
with zipfile.ZipFile('deploy.zip', 'w', zipfile.ZIP_DEFLATED) as zipf:
    for root, dirs, files in os.walk('deploy'):
        for file in files:
            full_path = os.path.join(root, file)
            rel_path = os.path.relpath(full_path, 'deploy')
            zipf.write(full_path, rel_path)
print('deploy.zip updated.')

# 2. Commit git changes
subprocess.run(['git', 'add', '.'], check=True)
subprocess.run(['git', 'commit', '-m', 'Fix UI styles and prepare deployment'], check=True)
print('Git committed.')
