import os
import sys
import matplotlib
matplotlib.use('Agg')
import matplotlib.pyplot as plt
import numpy as np
import StringIO
import urllib, base64

def graph(title, labels, data):
    fig, ax = plt.subplots()
    index = np.arange(len(labels))
    bar_width = 0.35
    opacity = 0.7
    plt.figure(figsize=(600/72, 300/72), dpi=72)

    plt.bar(index, data[0][1], bar_width,
        alpha=opacity,
        color='g',
        label=data[0][0]
    )

    if (len(data) == 2):
        plt.bar(index + bar_width, data[1][1], bar_width,
            alpha=opacity,
            color='b',
            label=data[1][0])

    #plt.ylabel('GiB')
    plt.xticks(index + bar_width, labels)
    plt.legend()

    fig = plt.gcf()
    imgdata = StringIO.StringIO()
    fig.savefig(imgdata, format='png')
    imgdata.seek(0)

    uri = 'data:image/png;base64,' + urllib.quote(base64.b64encode(imgdata.buf))
    print '%s' % uri