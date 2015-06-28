from sphinx.quickstart import generate
import sys


def createNewProject(path, project_name, author):
    d= {
        'path': path,
        'sep': True,
        'dot': '_',
        'project': project_name,
        'author': author,
        'version': '1.0',
        'release': '1.0',
        'suffix': '.rst',
        'master': 'index',
        'epub': False,
        'ext_autodoc': False,
        'ext_doctest': False,
        'ext_intersphinx': False,
        'ext_todo': False,
        'ext_coverage': False,
        'ext_pngmath': False,
        'ext_mathiax': False,
        'ext_ifconfig': True,
        'ext_todo': True,
        'ext_viewcode': False,
        'makefile': True,
        'batchfile': False,
    }
    generate(d)

if __name__ == '__main__':
    try:
       args = sys.argv
       createNewProject(args[1], args[2], args[3])
        
    except Exception, e:
       print "something went wrong!"
       print e
       print "Parameter: path project_name author"
      
