import subprocess

subprocess.run(['git', 'config', 'user.name', 'milham89'], check=True)
subprocess.run(['git', 'config', 'user.email', 'milham@example.com'], check=True)
subprocess.run(['git', 'add', '.'], check=True)
subprocess.run(['git', 'commit', '-m', 'Initial commit: Raport Digital application and deploy package'], check=True)
subprocess.run(['git', 'branch', '-M', 'main'], check=True)
print('committed successfully')
